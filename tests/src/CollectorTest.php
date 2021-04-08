<?php

namespace PMVC\PlugIn\math;

use PMVC\TestCase;
\PMVC\initPlugin(['math'=>false]);

class CollectorTest extends TestCase {

    private $_plug = 'math';

    public function testCollector() {
        $plug = \PMVC\plug($this->_plug);
        $arr = ['a', 'b', 'c'];        
        $counter = new FakeCounter();
        $counter->num = 2; 
        $counter->assert = $this;
        $result = $plug->collector(
            $arr, 
            [$counter]
        );
        $this->assertEquals([0=>[
            ["arr"=>["a", "b"], "current"=> "b"],
            ["arr"=>["b", "c"], "current"=> "c"],
        ]], $result);
    }
}

class FakeCounter implements CounterInterface
{
  public function count(array $arr, $current, $last)
  {
    static $i = 0;
    $assert = $this->assert;
    if (!$i) {
      $assert->assertEquals(['a', 'b'], $arr);
      $assert->assertEquals('b', $current);
    } else {
      $assert->assertEquals(['b', 'c'], $arr);
      $assert->assertEquals('c', $current);
    }
    $i++;
    return [
      'arr' => $arr,
      'current' => $current, 
    ];
  }
}
