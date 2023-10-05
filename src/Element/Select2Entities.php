<?php

namespace Algoritma\BehatExtension\Element;

use App\Tests\Behat\Context\ClearableInterface;
use Behat\Mink\Element\NodeElement;

/**
 * Fill field with many entities e.g. contexts field in send email form
 */
class Select2Entities extends Element implements ClearableInterface
{
    /**
     * {@inheritdoc}
     */
    public function setValue($values)
    {
        $this->getDriver()->waitForAjax();
        $this->clear();
        $this->focus();
        $values = true === is_array($values) ? $values : [$values];

        foreach ($values as $value) {
            $this->type($value);
            $searchResults = array_filter(
                $this->getSearchResults(),
                static function ($element) {
                    return !empty(trim($element->getText()));
                }
            );

            self::assertCount(
                1,
                $searchResults,
                sprintf(
                    'Too many results "%s" for "%s"',
                    implode(', ', array_map([$this, 'elementText'], $searchResults)),
                    $value
                )
            );
            $result = array_shift($searchResults);
            self::assertNotEquals('No matches found', $result->getText(), sprintf('No matches found for "%s"', $value));
            $result->click();
        }
    }

    /**
     * Return element text
     * @param NodeElement $element
     * @return string
     */
    public static function elementText(NodeElement $element)
    {
        return $element->getText();
    }

    /**
     * @return array|NodeElement[]
     */
    public function getSearchResults()
    {
        $this->getPage()->waitFor(5, function (NodeElement $element) {
            return null === $element->find('css', 'ul.select2-results li.select2-searching');
        });

        $resultHolder = $this->getPage()->findVisible('css', 'div.select2-drop-active ul.select2-result-sub');

        if (!$resultHolder) {
            $resultHolder = $this->getPage()->findVisible('css', 'div.select2-drop-active ul.select2-results');
        }

        if (!$resultHolder) {
            return [];
        }

        // Wait for show one result or go further
        $resultHolder->waitFor(5, function (NodeElement $element) {
            return 1 === count($element->findAll('css', 'li'));
        });

        return $resultHolder->findAll('css', 'li');
    }

    /**
     * Type into input
     * @param string $value
     */
    public function type($value)
    {
        $this->getDriver()->typeIntoInput($this->getXpath(), $value);
    }

    /**
     * Focus on input and wait until it will be focused
     */
    public function focus()
    {
        $mask = $this->getPage()->find('css', '#select2-drop-mask');
        if ($mask && $mask->isVisible()) {
            $mask->click();
        }

        if (!empty($this->getSearchResults())) {
            parent::click();
        }

        if (!$this->isActive()) {
            parent::click();
        }

        $this->waitFor(5, function (Select2Entities $element) {
            return $element->isActive();
        });
    }

    /**
     * Check if input is active and ready for input
     */
    public function isActive()
    {
        return $this->hasClass('select2-focused')
            && !$this->hasClass('select2-active')
            && $this->getParent()->getParent()->getParent()->hasClass('select2-container-active');
    }

    /**
     * Close search results dropdown
     */
    public function close()
    {
        $this->getDriver()->keyDown($this->getXpath(), 27);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $driver = $this->getDriver();
        $closeLinks = $this->getParent()->getParent()
            ->findAll('css', '.select2-search-choice-close');

        if (!$closeLinks) {
            return;
        }

        /** @var NodeElement $closeLink */
        foreach ($closeLinks as $closeLink) {
            // Click with javascript because element is not visible, only pseudo class ::before
            // https://symfony.com/doc/current/components/css_selector.html#limitations-of-the-cssselector-component
            $driver->executeJsOnXpath($closeLink->getXpath(), '{{ELEMENT}}.click()');
        }

        $this->waitFor(5, function () use ($closeLink) {
            return !$closeLink->isValid();
        });
    }

    public function getValue()
    {
        $valueElements = $this->getParent()->getParent()->findAll('css', 'li.select2-search-choice');

        return array_map(function (NodeElement $element) {
            return $element->getText();
        }, $valueElements);
    }
}
