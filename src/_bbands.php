<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\BBands';

class BBands
{
    public function __invoke()
    {
        return $this;
    }

    public function calculateAvg($data, array $avgs, $valueLocator = null)
    {
        if (is_null($valueLocator)) {
            $valueLocator = $this->_getDefaultValueLocator();
        }
        $avgTemp = [];
        $arrTemp = [];
        foreach ($data as $d) {
            foreach ($avgs as $avg) {
                $arrTemp[$avg][] = $valueLocator($d);
                $count = count($arrTemp[$avg]);
                if ($count >= $avg) {
                    $avgTemp[$avg][] = $this->_countAvg(
                        $arrTemp[$avg],
                        $d
                    );
                    array_shift($arrTemp[$avg]);
                }
            }
        }
        return $avgTemp;
    }

    private function _countAvg(array $arr, $d)
    {
        if (is_array($d) || is_object($d)) {
            $newD = new \PMVC\HashMap($d);
        } else {
            $newD = [];
        }
        $newD = \PMVC\set(
            $newD,
            [
                'mean'=>
                    round(array_sum($arr) / count($arr),2),
                'standardDeviation'=>
                    $this->
                    caller->
                    standard_deviation(
                        $arr,
                        true
                    )
            ]
        );
        return \PMVC\get($newD);
    }

    private function _getDefaultValueLocator()
    {
        return function ($d) {
            return $d;
        };
    }

    public function calculateBbands($avg, $xLocator, $valueLocator=null)
    {
        $areas = [];
        foreach ($avg as $a) {
            //it should have error when miss mean
            $mean = (float)$a['mean']; 
            //it should have error when miss standardDeviation
            $standardDeviation = (float)$a['standardDeviation']; 

            $lowerBB = $mean - $standardDeviation*2;
            $upperBB = $mean + $standardDeviation*2;
            $width = round(($upperBB - $lowerBB) / $mean,4) * 100;

            $area = [
                'x'  => $xLocator($a),
                'y0' => $lowerBB, //small num
                'y1' => $upperBB, //large num
                'width' => $width,
            ];
            if (!is_null($valueLocator)) {
                $value = $valueLocator($a);
                $area['b'] = round(($value - $lowerBB) / ($upperBB - $lowerBB), 4) * 100;
            }
            $areas[] = $area;
        }
        return $areas;
    }
}
