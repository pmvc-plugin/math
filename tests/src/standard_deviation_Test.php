<?php

namespace PMVC\PlugIn\math;

use PHPUnit_Framework_TestCase;

/**
 * Online Calculator
 * https://www.easycalculation.com/statistics/standard-deviation.php
 */

class StandardDeviationTest
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
        $population = $plug->
        standard_deviation($nums);
        $sample = $plug->
        standard_deviation($nums, true);
        //var_dump($population, $sample);
        $this->assertEquals(3.35, round($population, 2));
        $this->assertEquals(3.74, round($sample, 2));
    }

    function testSpeedCount()
    {
        $plug = \PMVC\plug($this->_plug);
        $nums = [
            15, 
            21,
            21,
            25,
            18
        ];
        $population = $plug->
        standard_deviation($nums, null, null, array_sum($nums));
        $this->assertEquals(3.35, round($population, 2));
    }
}
