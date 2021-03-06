<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\StatsStandardDeviation';

/**
 * Different between population and sample standard deviations
 * https://web.archive.org/web/http://libweb.surrey.ac.uk/library/skills/Number%20Skills%20Leicester/page_18.htm
 */

class StatsStandardDeviation
{
    function __invoke(array $a, $isSample=false, $valueLocator=null, $sum = 0)
    {
        if ($sum && !$valueLocator) {
            $arr = $a;
        } else {
            if (is_null($valueLocator)) {
                $valueLocator = function($v) {
                    return $v;
                };
            }
            $arr = [];
            foreach ($a as $val) {
                $val = $valueLocator($val);
                $sum += $val;
                $arr[] = $val;
            }
        }
        $n = count($arr);
        $mean = $sum / $n;
        if ($n === 0) {
            return !trigger_error('The array has zero elements');
        }
        if ($isSample) {
            if ($n === 1) {
                return !trigger_error('The array has only 1 element');
            }
            --$n;
        }
        $carry = 0.0;
        foreach ($arr as $val) {
            $d = $val - $mean;
            $carry += $d * $d;
        };
        return sqrt($carry / $n);
    }
}
