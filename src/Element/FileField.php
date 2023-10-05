<?php

namespace Algoritma\BehatExtension\Element;

use Behat\Testwork\Suite\Suite;

/**
 * Behat Element to upload the file to a form
 */
class FileField extends Element implements SuiteAwareInterface
{
    /**
     * @var Suite
     */
    protected $suite;

    /**
     * {@inheritdoc}
     */
    public function setValue($filename)
    {
        $this->attachFile($this->getFilePath($filename));
    }

    /**
     * {@inheritdoc}
     */
    public function setSuite(Suite $suite)
    {
        $this->suite = $suite;
    }

    /**
     * Try to find file in Fixtures folder of current suite,
     * then in TestFrameworkBundle/Tests/Behat/Fixtures
     *
     * @param string $filename Filename of attached file with extension e.g. charlie-sheen.jpg
     * @return string Absolute path to file
     *                e.g. /home/charlie/www/algoritmacrm/src/Algoritma/UserBundle/Tests/Behat/Feature/Fixtures/charlie-sheen.jpg
     */
    protected function getFilePath($filename)
    {
        $suitePaths[] = dirname(__DIR__, 2) . '/Tests/Behat/';
        foreach ($suitePaths as $suitePath) {
            $suitePath = is_dir($suitePath) ? $suitePath : dirname($suitePath);
            $path = $suitePath.DIRECTORY_SEPARATOR.'Fixtures'.DIRECTORY_SEPARATOR.$filename;

            if (file_exists($path)) {
                return $path;
            }
        }

        self::fail(sprintf('Can\'t find "%s" file in "%s"', $filename, implode(',', $suitePaths)));
    }
}
