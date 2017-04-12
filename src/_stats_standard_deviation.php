<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\StatsStandardDeviation';

/**
 * Different between population and sample standard deviations
 * https://web.archive.org/web/http://libweb.surrey.ac.uk/library/skills/Number%20Skills%20Leicester/page_18.htm
 */

class StatsStandardDeviation
{
    function __invoke(array $a, $isSample=false)
    {
        $n = count($a);
        if ($n === 0) {
            return !trigger_error('The array has zero elements');
        }
        if ($isSample && $n === 1) {
            return !trigger_error('The array has only 1 element');
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = $val - $mean;
            $carry += $d * $d;
        };
        if ($isSample) {
           --$n;
        }
        return sqrt($carry / $n);
    }
}
