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

    function calculateBbands($avg, $xLocator)
    {
        $area = [];
        foreach ($avg as $a) {
            //it should have error when miss mean
            $val = (float)$a['mean']; 
            //it should have error when miss standardDeviation
            $standardDeviation = (float)$a['standardDeviation']; 
            $area[] = [
                'x'  => $xLocator($a),
                'y0' => $val - $standardDeviation*2, //small num
                'y1' => $val + $standardDeviation*2, //large num
            ];
        }
        return $area;
    }
}
