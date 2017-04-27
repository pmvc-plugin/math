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

    public __construct($num)
    {
        $this->num = $num;
    }

    public count()
    {

    }
}
