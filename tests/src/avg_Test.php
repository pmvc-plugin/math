<?php

namespace PMVC\PlugIn\math;

use PHPUnit_Framework_TestCase;

class AvgTest
    extends PHPUnit_Framework_TestCase
{
    private $_plug = 'math';

    function testCalculate()
    {
        $plug = \PMVC\plug($this->_plug);
        $nums = [
            15, 
            21,
            21,
            25,
            18
        ];
        $avg = $plug->
            collector($nums, [$plug->avg(2), $plug->avg(4)]);
        $test = \PMVC\value($avg, [
            0,
            0,
            'mean' 
        ]);
        $this->assertEquals((15+21)/2, $test);
    }

}
