<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__ . '\Collector';

class Collector
{
    public function __invoke($data, array $operators, $valueLocator = null)
    {
        if (is_null($valueLocator)) {
            $valueLocator = $this->caller->default_value_locator();
        }
        $arrTemp = [];
        foreach ($operators as $oK => $operator) {
            $arrTemp[$oK] = [];
            /**
             * $operator->num
             * How many itmes should collect.
             */
            if (empty($operator->num)) {
                trigger_error(
                    'Operator should not have a zero num. ' .
                        print_r($operator, true)
                );
                unset($operators[$oK]);
            }
        }

        $isStartToCount = [];
        $checkStartToCount = function ($arrData, $opK, $operator) use (
            &$isStartToCount
        ) {
            $isStartToCount[$opK] = count($arrData) >= $operator->num;
            return $isStartToCount[$opK];
        };

        $lastTemp = [];
        $results = [];
        foreach ($data as $d) {
            $myData = $valueLocator($d);
            foreach ($operators as $opK => $operator) {
                $arrTemp[$opK][] = $myData; // collect enough sample to count such as avg.
                if (
                    !empty($isStartToCount[$opK]) ||
                    $checkStartToCount($arrTemp[$opK], $opK, $operator)
                ) {
                    $result = $operator->count(
                        $arrTemp[$opK],
                        $d, //use d for merge data
                        \PMVC\get($lastTemp, $opK)
                    );
                    $results[$opK][] = $result;
                    $lastTemp[$opK] = $result;
                    // remove last sample, all samples should keep with same $operator->num
                    array_shift($arrTemp[$opK]);
                }
            }
        }
        return $results;
    }
}
