<?php
namespace PMVC\PlugIn\math;

use PMVC\TestCase;

class MathTest extends TestCase
{
    private $_plug = 'math';
    function testPlugin()
    {
        ob_start();
        print_r(\PMVC\plug($this->_plug));
        $output = ob_get_contents();
        ob_end_clean();
        $this->haveString($this->_plug,$output);
    }
}
