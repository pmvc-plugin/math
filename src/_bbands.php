<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\BBands';

class BBands
{
    function __invoke()
    {
        return $this;
    }

    function calculateAvg($data, array $avgs, $valueLocator = null)
    {
        if (is_null($valueLocator)) {
            $valueLocator = $this->getDefaultValueLocator();
        }
        $avgTemp = [];
        $arrTemp = [];
        foreach ($data as $d) {
            foreach ($avgs as $avg) {
                $arrTemp[$avg][] = $valueLocator($d);
                if (count($arrTemp[$avg]) >= $avg) {
                    if (is_array($d) || is_object($d)) {
                        $newD = new \PMVC\HashMap($d);
                    } else {
                        $newD = [];
                    }
                    $newD = \PMVC\set(
                        $newD,
                        [
                            'mean'=> array_sum(
                                $arrTemp[$avg]
                            ) / $avg,
                            'standardDeviation'=>
                                $this->
                                caller->
                                standard_deviation(
                                    $arrTemp[$avg],
                                    true
                                )
                        ]
                    );
                    $avgTemp[$avg][] = \PMVC\get($newD);
                    $arrTemp[$avg] = [];
                }
            }
        }
        return $avgTemp;
    }

    function getDefaultValueLocator()
    {
        return function ($d) {
            return $d;
        };
    }

    function calculateBbands($avg, $xLocator, $yLocator = null)
    {
        if (is_null($yLocator)) {
            $yLocator = $this->getDefaultValueLocator();
        }
        $area = [];
        foreach ($avg as $a) {
            $val = $yLocator($a);
            $standardDeviation = $a['standardDeviation'];
            $area[] = [
                'x'  => $xLocator($a),
                'y0' => $val + $standardDeviation*2,
                'y1' => $val - $standardDeviation*2,
            ];
        }
        return $area;
    }
}
