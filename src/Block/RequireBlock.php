<?php

/**
 *  @author Yaasir Ketwaroo <ketwaroo.yaasir@gmail.com>
 */

namespace Ketwaroo\PhpParser\Block;

use Ketwaroo\PhpParser\Block;

/**
 * Description of RequireBlock
 */
class RequireBlock extends Block
{

    public function getBlockCloser()
    {
        return KTOK_SEMICOLON;
    }

    public function getBlockOpener()
    {
        return [T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE];
    }

    public function getBlockType()
    {
        return 'REQUIRE';
    }

    public function parseBlock()
    {
        $src = $this->getSource();
        $this->setEndToken(
                $src->findNextMatchingFrom(
                        $this->getStartToken()
                                ->getPointer()
                        , [KTOK_SEMICOLON]
                )
        );

        return $this;
    }

}
