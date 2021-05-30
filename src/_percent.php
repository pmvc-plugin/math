<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\Percent';

class Percent 
{
    public function __invoke($num)
    {
        return $this->caller->round($num * 100);
    }
}
