<?php

/**
 *  @author Yaasir Ketwaroo <ketwaroo.yaasir@gmail.com>
 */

namespace Ketwaroo\PhpParser\Pattern;

use Ketwaroo\PhpParser\Token;

/**
 * Description of TraitTokenPosition
 */
trait TraitTokenPosition
{

    /**
     *
     * @var Token 
     */
    protected $startToken, $endToken;

    /**
     * 
     * @return Token
     */
    public function getStartToken()
    {
        return $this->startToken;
    }

    /**
     * 
     * @return Token
     */
    public function getEndToken()
    {
        return $this->endToken;
    }

    /**
     * 
     * @param Token $startToken
     * @return static
     */
    public function setStartToken(Token $startToken)
    {
        $this->startToken = $startToken;
        return $this;
    }

    /**
     * 
     * @param Token $endToken
     * @return static
     */
    public function setEndToken(Token $endToken)
    {
        $this->endToken = $endToken;
        return $this;
    }

 

}
