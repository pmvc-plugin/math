<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\DefaultValueLocator';

class DefaultValueLocator
{
    public function __invoke()
    {
        return function ($d) {
            return $d;
        };
    }
}
