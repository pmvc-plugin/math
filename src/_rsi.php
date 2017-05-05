<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\GetRsi';

class GetRsi
{
    function __invoke($num)
    {
        return new Rsi($num);
    }
}

class Rsi {

    public $num;

    public function __construct($num)
    {
        /**
         * The num should set num+1, for known first day was up or down.
         */
        $this->num = $num+1;
        $this->math = \PMVC\plug('math');
    }

    public function count(array $arr, $current=[])
    {
        $last = array_shift($arr);
        $groupUps = [];
        $groupDowns = [];
        $total = $this->num - 1;
        foreach ($arr as $i) {
            $diff = $i - $last;
            if ($diff > 0) {
                $groupUps[]=$diff;
            } else {
                $groupDowns[]=abs($diff);
            }
            $last = $i;
        }
        $avgUps = array_sum($groupUps) / $total;
        $avgDowns = array_sum($groupDowns) / $total;
        $allAvg = $avgUps + $avgDowns;
        $rsi = 0;
        if ($allAvg) {
            $rsi = round($avgUps / $allAvg, 4) * 100;
        }
        return $this->
            math->
            merge_data($current, ['rsi'=>$rsi]);
    }
}
