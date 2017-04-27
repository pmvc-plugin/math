<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\GetAvg';

class GetAvg
{
    function __invoke($num)
    {
        return new Avg($num);
    }
}

class Avg {

    public $num;

    public function __construct($num)
    {
        $this->num = $num;
        $this->math = \PMVC\plug('math');
    }

    public function count(array $arr, $current, $last)
    {
        $mean = round(array_sum($arr) / count($arr),2);
        $params = [
            'mean'=>
                $mean,
            'standardDeviation'=>
                $this->
                math->
                standard_deviation(
                    $arr,
                    true
                )
        ];
        if ($last) {
            $lastMean = \PMVC\get($last, 'mean');
            $allMean = $lastMean + $mean;
            $params['slope'] = round(
                ($mean/$allMean - $lastMean/$allMean),
                4 
            ) * 100;
        }
        return $this->
            math->
            merge_data($current, $params);
    }
}
