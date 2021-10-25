<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\GetRsv';

/**
 * RSV (Raw Stochastic Value)
 *
 * @see https://zh.wikipedia.org/wiki/%E9%9A%8F%E6%9C%BA%E6%8C%87%E6%A0%87 
 */
class GetRsv
{
    function __invoke($num, $callback=null)
    {
        return new RSV($num, $callback);
    }
}

class RSV {

    public $num;
    private $_resetCallback;
    private $_rsvArr;
    private $_locator;

    public function __construct($days = 9, $customLocator = null)
    {
        $this->num = $days;
        $this->math = \PMVC\plug('math');
        $this->_locator = $this->math->stock_locator($customLocator);
    }

    private function _ma(array $arr, $period, $prev=null)
    {
        $multi = 1/$period;
        $prevMulti = 1-$multi;
        $ma = [array_shift($arr)];
        if (!is_null($prev)) {
            $ma = [$prev];
        }
        foreach ($arr as $k=>$n) {
            $prev = array_slice($ma, -1)[0];
            $ma[] = $multi * $n + $prevMulti * $prev;
        }
        return $ma;
    }

    public function calKD($rsvPeriod = null, $kPeriod = null, $prev = null)
    {
        if (is_null($rsvPeriod)) {
            $rsvPeriod = 3;
        }
        if (is_null($kPeriod)) {
            $kPeriod = 3;
        }
        $kArr = $this->_ma($this->_rsvArr, $rsvPeriod, \PMVC\get($prev, 'k'));
        $dArr = $this->_ma($kArr, $kPeriod, \PMVC\get($prev, 'd'));
        $keys = array_keys($this->_rsvArr);
        $result = [];
        foreach ($keys as $i=>$key) {
            $k = $kArr[$i];
            $d = $dArr[$i];
            $result[$key] = [
                'rsi' => $this->_rsvArr[$key],
                'k'   => $this->math->round($k),
                'd'   => $this->math->round($d),
                'j'   => $this->math->round(3 * $d - 2 * $k),
            ]; 
        }
        return $result;
    }

    public function count(array $arr, $current, $last)
    {
        $math = $this->math;
        $low = [];
        $high = [];
        $kArr = [];
        foreach ($arr as $a) {
          $item = $this->_locator->getValue($a);
          $low[] = $item['l']; 
          $high[] = $item['h']; 
          $price = $item['c'];
        }
        $minLow = min($low);
        $maxHigh = max($high);
        $value = $this->math->percent(($price - $minLow) / ( $maxHigh - $minLow));
        $this->_rsvArr[$item['t']] = $value;
        return $value;
    }
}
