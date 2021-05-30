<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\BBands';

class BBands
{

    private $_multiple = 2;
    private $_resetCallback;

    public function __invoke()
    {
        return $this;
    }

    public function setMultiple($m)
    {
        $this->_multiple = $m;
        return $this;
    }

    public function setResetCallback($resetCallback)
    {
        $this->_resetCallback = $resetCallback;
        return $this;
    }

    private function _round($num)
    {
        return round($num, 2);
    }

    /**
     * @param array    $avg
     * @param callable $xLocator     date locator
     * @param callable $valueLocator value locator
     */
    public function calculateBbands($avg, $xLocator, $valueLocator=null)
    {
        $areas = [];
        $lastWidth = 0;
        $lastBB = null;
        $sdMultiple = $this->_multiple;
        foreach ($avg as $aIndex=>$a) {
            //it should have error when miss mean
            $mean = (float)$a['mean']; 
            //it should have error when miss standardDeviation
            $standardDeviation = $this->_round($a['standardDeviation']); 
            $lowerBB = $this->_round($mean - $standardDeviation * $sdMultiple);
            $upperBB = $this->_round($mean + $standardDeviation * $sdMultiple);
            $width = $this->_round((($upperBB - $lowerBB) / $mean) * 100);

            $area = [
                'x'  => $xLocator($a, $aIndex),
                'y0' => $lowerBB, //small num
                'y1' => $upperBB, //large num
                'mean' => $mean,
                'standardDeviation' => $standardDeviation,
                'width' => $width,
            ];

            // lastWidth
            if ($width) {
                $area['widthDiffPercent'] = round((($width - $lastWidth) / $width) * 100, 2);
            } else {
                $area['widthDiffPercent'] = 0;
            }
            $lastWidth = $width;
            if (!is_null($valueLocator)) { // if can get raw value not necessary
                $value = $valueLocator($a);
                if ($upperBB !== $lowerBB) {
                    $bbands = round((($value - $lowerBB) / ($upperBB - $lowerBB)) * 100, 2);
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
            if (is_callable($this->_resetCallback)) {
                call_user_func_array(
                    $this->_resetCallback,
                    [&$area]
                );
            }
            $areas[] = $area;
        }
        return $areas;
    }
}
