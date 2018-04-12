<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\CountSlope';

class CountSlope
{
    function __invoke($a, $b, $all)
    {
        $slope = round(
            ($b/$all - $a/$all) * 100,
            2
        );
        return $slope;
    }
}
