<?php

namespace PMVC\PlugIn\math;

use PHPUnit_Framework_TestCase;

class CountSlopeTest
    extends PHPUnit_Framework_TestCase
{
    private $_plug = 'math';
    function testSlope()
    {
        $plug = \PMVC\plug($this->_plug);
        $a = 0;
        $b = 50;
        $slope = $plug->count_slope($a, $b, 100);
        $this->assertEquals(50, $slope); 
    }
}
