<?php

/**
 *  @author Yaasir Ketwaroo <ketwaroo.yaasir@gmail.com>
 */
require '../../vendor/autoload.php';

/**
 * doc
 */
class A extends ArrayObject implements ArrayAccess
{

    public function functionName($param)
    {
        echo __LINE__;
        echo __LINE__;
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

foreach ($src->getBlocks('T_CLASS') as $c)
{
    prnt($c->getClassName());
}