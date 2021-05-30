<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__ . '\math';

interface CounterInterface
{
    public function count(array $arr, $current, $last);
}

class math extends \PMVC\PlugIn
{
    public function init()
    {
        if (!is_numeric($this['precision'])) {
            $this['precision'] = 2;
        }
    }
}
