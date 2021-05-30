<?php

namespace PMVC\PlugIn\math;

use PMVC\TestCase;

class EmaTest extends TestCase
{
    private $_plug = 'math';

    public function testEma()
    {
        $plug = \PMVC\plug($this->_plug);
        $nums = [
            2,
            4,
            6,
            8,
            12
        ];
        $actural = $plug->calEma($nums, 2);
        $expected = [
            2,
            3.33,
            5.11,
            7.04,
            10.35
        ];
        $this->assertEquals($expected, $actural);
    }
}
