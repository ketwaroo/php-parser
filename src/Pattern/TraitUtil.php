<?php

/*
 *  @author Yaasir Ketwaroo <ketwaroo.yaasir@gmail.com>
 */

namespace Ketwaroo\PhpParser\Pattern;

/**
 *
 * @author Administrator
 */
trait TraitUtil
{
    protected function utilUnixifyString($str)
    {
        return str_replace(["\r\n", "\r"], "\n", $str);
    }

    protected function utilCountLines($str)
    {
        return substr_count($this->utilUnixifyString($str), "\n");
    }
}
