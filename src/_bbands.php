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

    public function setMultiple($m)
    {
        $this->_multiple = $m;
        return $this;
    }

    public function calculateBbands($avg, $xLocator, $valueLocator=null)
    {
        $areas = [];
        $lastWidth = 0;
        $lastBB = null;
        $sdMultiple = $this->_multiple;
        foreach ($avg as $a) {
            //it should have error when miss mean
            $mean = (float)$a['mean']; 
            //it should have error when miss standardDeviation
            $standardDeviation = (float)$a['standardDeviation']; 
            $lowerBB = $mean - $standardDeviation * $sdMultiple;
            $upperBB = $mean + $standardDeviation * $sdMultiple;
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
            if ($width) {
                $area['widthDiffPercent'] = round(($width - $lastWidth) / $width, 4) * 100;
            } else {
                $area['widthDiffPercent'] = 0;
            }
            $lastWidth = $width;
            if (!is_null($valueLocator)) { // if can get raw value not necessary
                $value = $valueLocator($a);
                if ($upperBB !== $lowerBB) {
                    $bbands = round(($value - $lowerBB) / ($upperBB - $lowerBB), 4) * 100;
                } else {
                    $bbands = 0;
                }
                $area['bbandsData'] = $value;
                if ($lastBB) {
                    $area['bbandsDiff'] = round($bbands - $lastBB, 2);
                } else {
                    $area['bbandsDiff'] = 0;
                }
                $area['bbands'] = $lastBB = $bbands;
            }
            $areas[] = $area;
        }
        return $areas;
    }
}
