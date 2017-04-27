<?php

namespace PMVC\PlugIn\math;

use PHPUnit_Framework_TestCase;

class RsiTest
    extends PHPUnit_Framework_TestCase
{
    private $_plug = 'math';

    function testCalculate()
    {
        $plug = \PMVC\plug($this->_plug);
        $nums = [
            50, 
            52,
            53,
            52,
            54,
            58,
            56
        ];
        $rsi = $plug->
            collector($nums, [$plug->rsi(6)]);
        $actual = \PMVC\value($rsi, [
            0,
            0,
            'rsi' 
        ]);
        $this->assertEquals(75, $actual);
    }

}
