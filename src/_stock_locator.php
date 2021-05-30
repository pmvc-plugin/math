<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__ . '\GetStockLocator';

class GetStockLocator {
    public function __invoke($customLocator = null)
    {
        $locator =  new StockLocator();
        return $locator($customLocator);
    }
}

class StockLocator
{
    private $_locators;

    public function __invoke($customLocator = null)
    {
        if (!is_null($customLocator) || empty($this->_locators)) {
            $this->reset($customLocator);
        }
        return $this;
    }

    public function getValue($data)
    {
        $keyArr = array_keys($this->_locators);
        $result = [];
        foreach ($keyArr as $k) {
            $result[$k] = $this->_locators[$k]($data);
        }
        return $result;
    }

    public function reset($customLocator = null)
    {
        $defaultLocators = [
            't' => function ($item) {
                return \PMVC\get($item, 't');
            },
            'o' => function ($item) {
                return \PMVC\get($item, 'o');
            },
            'h' => function ($item) {
                return \PMVC\get($item, 'h');
            },
            'l' => function ($item) {
                return \PMVC\get($item, 'l');
            },
            'c' => function ($item) {
                return \PMVC\get($item, 'c');
            },
        ];
        $keyArr = array_keys($defaultLocators);
        foreach ($keyArr as $k) {
            $this->_locators[$k] = \PMVC\get(
                $customLocator,
                $k,
                function() use ($defaultLocators, $k) {return $defaultLocators[$k];}
            );
        }
    }
}
