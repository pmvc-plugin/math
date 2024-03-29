<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__ . '\BBands';

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

    /**
     * @param array    $avg
     * @param callable $xLocator     date locator
     * @param callable $valueLocator value locator
     */
    public function calculateBbands($avg, $xLocator, $valueLocator = null)
    {
        $areas = [];
        $lastWidth = 0;
        $lastBB = null;
        $sdMultiple = $this->_multiple;
        $caller = $this->caller;

        foreach ($avg as $aIndex => $a) {
            $mean = (float) \PMVC\get($a, 'mean');
            if (!$mean) {
                //it should have error when miss mean
                return \PMVC\triggerJson(
                    'Count bbands faild, mean is not correct.',
                    $a,
                    E_USER_WARNING
                );
            }
            $standardDeviation = $caller->round(
                \PMVC\get($a, 'standardDeviation')
            );
            if (!is_numeric($standardDeviation)) {
                //it should have error when miss standardDeviation
                return \PMVC\triggerJson(
                    'Count bbands faild, standardDeviation is not correct.',
                    $a,
                    E_USER_WARNING
                );
            }
            $lowerBB = $caller->round($mean - $standardDeviation * $sdMultiple);
            $upperBB = $caller->round($mean + $standardDeviation * $sdMultiple);
            $width = $caller->round((($upperBB - $lowerBB) / $mean) * 100);

            $area = [
                'x' => $xLocator($a, $aIndex),
                'y0' => $lowerBB, //small num
                'y1' => $upperBB, //large num
                'mean' => $mean,
                'standardDeviation' => $standardDeviation,
                'width' => $width,
            ];

            // lastWidth
            if ($width) {
                $area['widthDiffPercent'] = round(
                    (($width - $lastWidth) / $width) * 100,
                    2
                );
            } else {
                $area['widthDiffPercent'] = 0;
            }
            $lastWidth = $width;
            if (!is_null($valueLocator)) {
                // if can get raw value not necessary
                $value = $valueLocator($a);
                if ($upperBB !== $lowerBB) {
                    $bbands = round(
                        (($value - $lowerBB) / ($upperBB - $lowerBB)) * 100,
                        2
                    );
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
                call_user_func_array($this->_resetCallback, [&$area]);
            }
            $areas[] = $area;
        }
        return $areas;
    }
}
