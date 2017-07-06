<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\GetAvg';

class GetAvg
{
    function __invoke($num, $callback=null)
    {
        return new Avg($num, $callback);
    }
}

class Avg {

    public $num;
    private $_resetCallback;

    public function __construct($num, $resetCallback = null)
    {
        $this->num = $num;
        $this->math = \PMVC\plug('math');
        $this->_resetCallback = $resetCallback;
    }

    public function count(array $arr, $current, $last)
    {
        $sum = array_sum($arr);
        $mean = round($sum / count($arr),2);
        $standardDeviation = $this->
            math->
            standard_deviation(
                $arr,
                true,
                null,
                $sum
            );
        $params = [
            'mean'=> $mean,
            'standardDeviation'=> $standardDeviation
        ];
        if ($last) {
            $lastMean = \PMVC\get($last, 'mean');
            $allMean = $lastMean + $mean;
            $params['slope'] = round(
                ($mean/$allMean - $lastMean/$allMean),
                4 
            ) * 100;
        }
        $result =  $this->
            math->
            merge_data($current, $params);
        if (is_callable($this->_resetCallback)) {
            call_user_func_array(
                $this->_resetCallback,
                [&$result]
            );
        }
        return $result;
    }
}
