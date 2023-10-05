<?php

namespace Algoritma\BehatExtension\Element;

interface AlgoritmaPageObjectAware
{
    /**
     * @param AlgoritmaElementFactory $elementFactory
     *
     * @return void
     */
    public function setElementFactory(AlgoritmaElementFactory $elementFactory);

    /**
     * @param AlgoritmaPageFactory $elementFactory
     *
     * @return void
     */
    public function setPageFactory(AlgoritmaPageFactory $elementFactory);
}
