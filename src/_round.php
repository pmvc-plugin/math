<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\MyRound';

class MyRound 
{
    public function __invoke($num)
    {
        return round($num, $this->caller['precision']);
    }
}
