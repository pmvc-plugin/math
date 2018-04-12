<?php

namespace PMVC\PlugIn\math;

use PHPUnit_Framework_TestCase;

class RebaseTest
    extends PHPUnit_Framework_TestCase
{

    private $_plug = 'math';

    /**
     * @dataProvider itemProvider
     */
    function testRebase($nums, $base, $expected)
    {
        $plug = \PMVC\plug($this->_plug);
        $actual = $plug->rebase($nums, $base);
        $this->assertEquals($expected, $actual);
    }

    function itemProvider()
    {
        return [
            [
                /*nums*/
                [
                    'a'=>10,
                    'b'=>20
                ],
                /*base*/
                20,
                /*expected*/
                [
                    'a'=>50,
                    'b'=>100
                ],
            ],
            [
                /*nums*/
                [
                    'a'=>100,
                    'b'=>500
                ],
                /*base*/
                1000,
                /*expected*/
                [
                    'a'=>10,
                    'b'=>50,
                ],
            ],
        ];
    }
}
