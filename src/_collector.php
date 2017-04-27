<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\Collector';

class Collector
{
    public function __invoke(
        $data,
        array $operators,
        $valueLocator = null
    ) {
        if (is_null($valueLocator)) {
            $valueLocator = $this->
                caller->
                default_value_locator();
        }
        $results = [];
        $arrTemp = [];
        $lastTemp = [];
        foreach ($operators as $oK=>$operator) {
            $arrTemp[$oK] = [];
        }
        foreach ($data as $d) {
            foreach ($operators as $oK=>$operator) {
                $arrTemp[$oK][] = $valueLocator($d);
                $count = count($arrTemp[$oK]);
                if ($count >= $operator->num) {
                    $result = $operator->count(
                        $arrTemp[$oK],
                        $d, //use d for merge data
                        \PMVC\get($lastTemp, $oK) 
                    );
                    $results[$oK][] = $result;
                    $lastTemp[$oK] = $result;
                    array_shift($arrTemp[$oK]);
                }
            }
        }
        return $results;
    }
}
