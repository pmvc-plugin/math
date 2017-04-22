<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\BBands';

class BBands
{

    private $_multiple = 2;

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
        $lastTemp = [];
        foreach ($data as $d) {
            foreach ($avgs as $avg) {
                $arrTemp[$avg][] = $valueLocator($d);
                $count = count($arrTemp[$avg]);
                if ($count >= $avg) {
                    $avgCountResult = $this->_countAvg(
                        $arrTemp[$avg],
                        $d,
                        \PMVC\get($lastTemp, $avg) 
                    );
                    $avgTemp[$avg][] = $avgCountResult;
                    $lastTemp[$avg] = $avgCountResult;
                    array_shift($arrTemp[$avg]);
                }
            }
        }
        return $avgTemp;
    }

    private function _countAvg(array $arr, $d, $last)
    {
        if (is_array($d) || is_object($d)) {
            $newD = new \PMVC\HashMap($d);
        } else {
            $newD = [];
        }
        $mean = round(array_sum($arr) / count($arr),2);
        $params = [
            'mean'=>
                $mean,
            'standardDeviation'=>
                $this->
                caller->
                standard_deviation(
                    $arr,
                    true
                )
        ];
        if ($last) {
            $lastMean = \PMVC\get($last, 'mean');
            $allMean = $lastMean + $mean;
            $params['slope'] = round(
                ($mean/$allMean - $lastMean/$allMean),
                4 
            ) * 100;
        }
        $newD = \PMVC\set(
            $newD,
            $params
        );
        return \PMVC\get($newD);
    }

    private function _getDefaultValueLocator()
    {
        return function ($d) {
            return $d;
        };
    }

    public function setMultiple($m)
    {
        $this->_multiple = $m;
        return $this;
    }

    public function calculateBbands($avg, $xLocator, $valueLocator=null)
    {
        $areas = [];
        $lastWidth = null;
        $lastBB = null;
        foreach ($avg as $a) {
            //it should have error when miss mean
            $mean = (float)$a['mean']; 
            //it should have error when miss standardDeviation
            $standardDeviation = (float)$a['standardDeviation']; 

            $lowerBB = $mean - $standardDeviation * $this->_multiple;
            $upperBB = $mean + $standardDeviation * $this->_multiple;
            $width = round(($upperBB - $lowerBB) / $mean,4) * 100;

            $area = [
                'x'  => $xLocator($a),
                'y0' => $lowerBB, //small num
                'y1' => $upperBB, //large num
                'mean' => $mean,
                'standardDeviation' => $standardDeviation,
                'width' => $width,
            ];

            // lastWidth
            if (!is_null($lastWidth)) {
                $area['widthDiff'] = round($width - $lastWidth, 2);
            }
            $lastWidth = $width;
            if (!is_null($valueLocator)) {
                $value = $valueLocator($a);
                if ($upperBB !== $lowerBB) {
                    $area['bbands'] = round(($value - $lowerBB) / ($upperBB - $lowerBB), 4) * 100;
                } else {
                    $area['bbands'] = 0;
                }
                $area['bbandsData'] = $value;
                if (!is_null($lastBB)) {
                    $area['bbandsDiff'] = round($area['bbands'] - $lastBB, 2);
                }
                $lastBB = $area['bbands'];
            }
            $areas[] = $area;
        }
        return $areas;
    }
}
