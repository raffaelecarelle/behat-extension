<?php

namespace Algoritma\BehatExtension\ServiceContainer;

use Algoritma\BehatExtension\Driver\AlgoritmaSelenium2Factory;
use Algoritma\BehatExtension\Listener\SessionsListener;
use Behat\Testwork\ServiceContainer\Extension as TestworkExtension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use FriendsOfBehat\SymfonyExtension\ServiceContainer\SymfonyExtension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\DecoratorServicePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Basic behat extension that contains logic which prepare environment while testing, load configuration, etc.
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class AlgoritmaBehatExtension implements TestworkExtension
{
    private const CONFIG_PATH = '/Behat/behat.yml';
    private const ELEMENTS_CONFIG_ROOT = 'elements';
    private const PAGES_CONFIG_ROOT = 'pages';

    /**
     * {@inheritdoc}
     */
    public function getConfigKey(): string
    {
        return 'algoritma_test';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        $minkExtension = $extensionManager->getExtension('mink');
        $minkExtension->registerDriverFactory(new AlgoritmaSelenium2Factory());
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->loadBootstrap($container);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
        $loader->load('services.yml');
        $loader->load('kernel_services.yml');

        // Remove reboot kernel after scenario because we have isolation in feature layer instead of scenario
        $container->removeDefinition('fob_symfony.kernel_orchestrator');

        $container->addCompilerPass(new DecoratorServicePass());
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $container->get(SymfonyExtension::KERNEL_ID)->registerBundles();

        $this->transferApplicationParameters($container);
        $this->processBundleBehatConfigurations($container);
        $this->replaceSessionListener($container);

        $container->get(SymfonyExtension::KERNEL_ID)->shutdown();
    }

    private function loadBootstrap(ContainerBuilder $container)
    {
        /** @var KernelInterface $kernel */
        $projectDir = $container->getParameter('paths.base');
        $bootstrapFile = $projectDir . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'bootstrap.php';
        if (file_exists($bootstrapFile)) {
            require_once $bootstrapFile;
        }
    }

    private function transferApplicationParameters(ContainerBuilder $container): void
    {
        /** @var KernelInterface $kernel */
        $kernel = $container->get(SymfonyExtension::KERNEL_ID);
        $container->setParameter('kernel.log_dir', $kernel->getLogDir());
        $container->setParameter('kernel.project_dir', $kernel->getProjectDir());
        $container->setParameter('kernel.secret', $kernel->getContainer()->getParameter('kernel.secret'));
    }

    private function replaceSessionListener(ContainerBuilder $container): void
    {
        $container
            ->getDefinition('mink.listener.sessions')
            ->setClass(SessionsListener::class);
    }

    private function processBundleBehatConfigurations(ContainerBuilder $container): void
    {
        $processor = new Processor();
        $configuration = new BehatBundleConfiguration($container);
        $pages = [];
        $elements = [];

        foreach ($this->getConfigPathsPrefixes($container) as $pathPrefix) {
            $configFile = str_replace('/', DIRECTORY_SEPARATOR, $pathPrefix . self::CONFIG_PATH);
            if (!is_file($configFile)) {
                continue;
            }

            $config = Yaml::parse(file_get_contents($configFile));
            $processedConfiguration = $processor->processConfiguration($configuration, $config);

            $this->appendConfiguration($pages, $processedConfiguration[self::PAGES_CONFIG_ROOT]);
            $this->appendConfiguration($elements, $processedConfiguration[self::ELEMENTS_CONFIG_ROOT]);
        }

        $this->loadAppBehatServices($container);

        $container->getDefinition('algoritma_element_factory')->replaceArgument(2, $elements);
        $container->getDefinition('algoritma_page_factory')->replaceArgument(1, $pages);
    }

    private function getConfigPathsPrefixes(ContainerBuilder $container): array
    {
        /** @var KernelInterface $kernel */
        $kernel = $container->get(SymfonyExtension::KERNEL_ID);
        $configPrefixes = [];

        $configPrefixes[] = $kernel->getProjectDir() . '/tests';

        return $configPrefixes;
    }

    private function loadAppBehatServices(ContainerBuilder $container): void
    {
        /** @var KernelInterface $kernel */
        $kernel = $container->get(SymfonyExtension::KERNEL_ID);

        $loader = new YamlFileLoader($container, new FileLocator($kernel->getProjectDir() . '/config'));
        $loader->load('behat_services.yml');
    }

    private function appendConfiguration(array &$baseConfig, array $config): void
    {
        foreach ($config as $key => $value) {
            if (array_key_exists($key, $baseConfig)) {
                throw new \InvalidArgumentException(sprintf('Configuration with "%s" key is already defined', $key));
            }

            $baseConfig[$key] = $value;
        }
    }
}
