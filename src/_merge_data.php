<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\MergeData';

class MergeData
{
    function __invoke($current, $new)
    {
        if (is_array($current) || is_object($current)) {
            $result = new \PMVC\HashMap($current);
        } else {
            $result = [];
        }
        \PMVC\set(
            $result,
            $new
        );
        return \PMVC\get($result);
    }
}
