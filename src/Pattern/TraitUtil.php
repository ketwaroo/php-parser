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
    protected static function utilUnixifyString($str)
    {
        return str_replace(["\r\n", "\r"], "\n", $str);
    }

    protected static function utilCountLines($str)
    {
        return substr_count(static::utilUnixifyString($str), "\n");
    }
}
