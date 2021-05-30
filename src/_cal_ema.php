<?php

namespace PMVC\PlugIn\math;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__ . '\CalEMA';

/**
 * EMA (Exponential Moving Average)
 * @see https://www.investopedia.com/ask/answers/122314/what-exponential-moving-average-ema-formula-and-how-ema-calculated.asp
 *
 * https://goodcalculators.com/exponential-moving-average-calculator/
 */
class CalEMA
{
    public function __invoke(array $arr, $num)
    {
        $multi = 2 / ($num + 1);
        $prevMulti = 1 - $multi;
        $ema = [array_shift($arr)];
        foreach ($arr as $n) {
            $prev = array_slice($ema, -1)[0];
            $ema[] = $this->caller->round($multi * $n + $prevMulti * $prev);
        }
        return $ema;
    }
}
