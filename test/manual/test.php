<?php

define('MOO', __DIR__);
require MOO . '/../../vendor/autoload.php';

/**
 * doc
 */
class A extends ArrayObject implements ArrayAccess
{

    public function functionName($param)
    {
        echo __LINE__;
        echo T_INCLUDE_ONCE, T_REQUIRE, T_;
    }

}

/**
 * doc 2
 */
class Z extends A
{

    public function moo($param)
    {
        // single line comment

        if (false):

// do something false
            include_once 'MOO';

        else:

// do something true

        endif;
    }

    protected function meow($param, $paeamt)
    {
        foreach ([] as $k => $v):

            echo $v;

        endforeach;

        foreach ([] as $x => $y)
        {

            echo $y;
        }

        switch (true)
        {

            case 1:
                break;
            case 0:
                break;

            default:
        }
    }

}

$src = new Ketwaroo\PhpParser\Source(file_get_contents(__FILE__));

foreach($src->getBlocks() as $TYPE => $blocks)
{
    foreach ($blocks as $b)
    {
        prnt("$TYPE $b");
    }
}