<?php

/**
 *  @author Yaasir Ketwaroo <ketwaroo.yaasir@gmail.com>
 */

namespace Ketwaroo\PhpParser;

use Ketwaroo\PhpParser\Pattern\InterfaceBlockHandler;
use Ketwaroo\PhpParser\Pattern\InterfaceConstants;

/**
 * Description of PhpBlock
 */
abstract class Block implements InterfaceBlockHandler, InterfaceConstants
{

    use Pattern\TraitTokenPosition;

    protected $source;

    protected $parentBlock;

    protected $blockTypeToken;

    public function __construct(Source $source, Token $blockTypeToken, Block $parentBlock = null)
    {
        $this->setSource($source)
                ->setStartToken($blockTypeToken)
                ->setBlockTypeToken($blockTypeToken)
                ->setParentBlock($parentBlock);

        $this->parseBlock();
    }

    abstract public function parseBlock();

    abstract public function getBlockType();

    /**
     * 
     * @return Token
     */
    public function findClosingToken(Token $startToken)
    {
        $source = $this->getSource();

        $blockOpener = $this->getBlockOpener();
        $blockCloser = $this->getBlockCloser();

        $opener = $source->findNextMatchingFrom($startToken->getPointer(), $blockOpener);

        $level         = 0;
        $max           = $source->getEndPointer();
        $openerPointer = $opener->getPointer();

        for ($currentPointer = $openerPointer; $currentPointer <= $max; $currentPointer++)
        {

            $currentToken = $source->getTokenAt($currentPointer);

            if ($currentToken->is($blockOpener))
            {
                $level++;
                continue;
            }

            if ($currentToken->is($blockCloser))
            {
                $level--;

                if ($level === 0)
                {
                    break;
                }
                else
                {
                    continue;
                }
            }
        }

        return $currentToken;
    }

    /**
     * 
     * @return Source
     */
    public function getSource()
    {
        return $this->source;
    }

    public function getParentBlock()
    {
        return $this->parentBlock;
    }

    protected function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    protected function setParentBlock(Block $parentBlock = null)
    {
        $this->parentBlock = $parentBlock;
        return $this;
    }

    /**
     * 
     * @return Token
     */
    public function getBlockTypeToken()
    {
        return $this->blockTypeToken;
    }

    /**
     * 
     * @param Token $blockTypeToken
     * @return static
     */
    public function setBlockTypeToken(Token $blockTypeToken)
    {
        $this->blockTypeToken = $blockTypeToken;
        return $this;
    }

    public function __toString()
    {
        $ret    = '';
        $start  = $this->getStartToken()->getPointer();
        $end    = $this->getEndToken()->getPointer();
        $source = $this->getSource();
        for ($i = $start; $i <= $end; $i++)
        {
            $ret.='' . $source->getTokenAt($i);
        }
        return $ret;
    }

}
