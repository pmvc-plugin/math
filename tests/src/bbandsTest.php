<?php

namespace PMVC\PlugIn\math;

use PMVC\TestCase;

class BbandsTest extends TestCase
{
    private $_plug = 'math';

    public function testBbands()
    {
        $plug = \PMVC\plug($this->_plug);
        $nums = [15, 21, 21, 25, 18];
        $avg = $plug->collector($nums, [$plug->avg(3)]);
        $actural = $plug
            ->bbands()
            ->setMultiple(1)
            ->calculateBbands($avg[0], function ($item, $index) {
                return $index;
            });
        $expected = [
            [
                'x' => 0,
                'y0' => 15.54,
                'y1' => 22.46,
                'mean' => 19,
                'standardDeviation' => 3.46,
                'width' => 36.42,
                'widthDiffPercent' => 100,
            ],
            [
                'x' => 1,
                'y0' => 20.02,
                'y1' => 24.64,
                'mean' => 22.33,
                'standardDeviation' => 2.31,
                'width' => 20.69,
                'widthDiffPercent' => -76.03,
            ],
            [
                'x' => 2,
                'y0' => 17.82,
                'y1' => 24.84,
                'mean' => 21.33,
                'standardDeviation' => 3.51,
                'width' => 32.91,
                'widthDiffPercent' => 37.13,
            ],
        ];
        $this->assertEquals($expected, $actural);
    }
}
