<?php

/**
 *  @author Yaasir Ketwaroo <ketwaroo.yaasir@gmail.com>
 */

namespace Ketwaroo\PhpParser\Block;

use Ketwaroo\PhpParser\Block;

/**
 * Description of Class
 */
class ClassBlock extends Block
{

    protected $className = null;

    public function getClassName()
    {
        if (null === $this->className)
        {
            $n = $this->getSource()
                    ->findNextMatchingFrom(
                    $this->getBlockTypeToken()->getPointer()
                    , T_STRING
                    , [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]
            );

            $this->className = $n->getValue();
        }
        return $this->className;
    }

    public function getBlockType()
    {
        return 'T_CLASS';
    }

    /**
     * 
     * @return ClassBlock
     */
    public function parseBlock()
    {
        $src = $this->getSource();

        $startToken = $src->findPreviousMatchingFrom(
                $this->getStartToken()->getPointer()
                , T_DOC_COMMENT
                , [ T_WHITESPACE]
        );

        $endToken = $this->setStartToken($startToken)
                ->findClosingToken($startToken); //find matching closing

        $this->setEndToken($endToken);

        return $this;
    }

    public function getBlockOpener()
    {
        return KTOK_CURLY_OPEN;
    }

    public function getBlockCloser()
    {
        return KTOK_CURLY_CLOSE;
    }

}
