<?php

namespace Algoritma\BehatExtension\ServiceContainer;

use FriendsOfBehat\SymfonyExtension\ServiceContainer\SymfonyExtension;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Configuration definition of `/Tests/Behat/behat.yml` for bundles.
 */
class BehatBundleConfiguration implements ConfigurationInterface
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
        $this->kernel = $container->get(SymfonyExtension::KERNEL_ID);
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('algoritma_behat_extension');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('elements')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('selector')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($value) {
                                        return ['type' => 'css', 'locator' => $value];
                                    })
                                ->end()
                                ->children()
                                    ->scalarNode('type')->isRequired()
                                        ->validate()
                                            ->ifNotInArray(['css', 'xpath'])
                                            ->thenInvalid('Invalid selector type %s')
                                        ->end()
                                    ->end()
                                    ->scalarNode('locator')->isRequired()->end()
                                ->end()
                            ->end()
                            ->scalarNode('class')
                                ->defaultValue('Algoritma\BehatExtension\Element\Element')
                            ->end()
                            ->variableNode('options')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('pages')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('class')->isRequired()->end()
                            ->scalarNode('route')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
