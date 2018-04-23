<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\rebase';

class rebase
{
    public function __invoke(array $items, $base)
    {
        if ($base < 100) {
            return $this->_lessThan100($items, $base);
        } else {
            return $this->_greaterThan100($items, $base);
        }
    }

    private function _lessThan100(array $items, $base)
    {
        $multiple = $base / 100;
        foreach ($items as &$v) {
            $v = round($v / $multiple);
        }
        return $items;
    }

    private function _greaterThan100(array $items, $base)
    {
        $multiple = 100 / $base;
        foreach ($items as &$v) {
            $v = round($v * $multiple);
        }
        return $items;
    }
}
