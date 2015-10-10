<?php

/**
 *  @author Yaasir Ketwaroo <ketwaroo.yaasir@gmail.com>
 */

namespace Ketwaroo\PhpParser;

use Ketwaroo\PhpParser\Token;
use Ketwaroo\PhpParser\Block;
use Ketwaroo\PhpParser\Pattern\InterfaceConstants;

/**
 * Description of File
 */
class Source implements InterfaceConstants
{

    use Pattern\TraitTokenPosition;

    /**
     *
     * @var Block[] 
     */
    protected $blocks = [];

    protected $undefinedTokens = [];

    protected $tokenHandlerCache = [];

    /**
     *
     * @var Token[] 
     */
    public $tokens = [];

    /**
     *
     * @var int 
     */
    protected $startPointer;

    /**
     *
     * @var int
     */
    protected $endPointer;

    public function __construct($source)
    {

        $constants = get_defined_constants(true);
        $prefix    = static::UNDEFINED_TOKENS_CONSTANT_PREFIX;
        foreach ($constants['user'] as $con => $val)
        {
            // undefined token value should always be single line. multilines are whitespace or comment.
            if ($prefix === substr($con, 0, strlen($prefix)))
            {
                $this->undefinedTokens[$val] = $con;
            }
        }


        $this->tokens = token_get_all($source);

        $this->setStartPointer(0);
        $this->setEndPointer(count($this->tokens) - 1);

        $this->parseSourceTokens();
        $this->parseBlock();

        // parseout the blocks.
    }

    protected function parseSourceTokens()
    {

        // parse everything to Token objects.
        foreach ($this->tokens as $pointer => $tok)
        {

            if ($tok instanceof Token)
            {
                continue;
            }

            if (is_array($tok))
            {
                $this->tokens[$pointer] = $this->makeToken($tok[0], $tok[1], $tok[2], $pointer);
            }
            else
            {
                $line = ($pointer - 1 === 0 ) ? 1 : ($this->tokens[$pointer - 1]->getLine() + $this->tokens[$pointer - 1]->countLines());

                $this->tokens[$pointer] = $this->makeToken($tok, $tok, $line, $pointer);
            }
        }
    }

    public function makeToken($tokenConstant, $tokenValue, $line = null, $pointer = null)
    {
        return new Token($this->getTokenName($tokenConstant), $tokenValue, $line, $pointer);
    }

    public function parseBlock()
    {
        $startPointer = $this->getStartPointer();
        $endPointer   = $this->getEndPointer();

        for ($pointer = $startPointer; $pointer <= $endPointer; $pointer++)
        {
            if (null !== ($token = $this->getTokenAt($pointer)))
            {

                if (null !== ($block = $this->makeBlock($token)))
                {

                    $this->addBlock($block);

                    $pointer = 1 + $block->getEndToken()->getPointer();
                }
            }
        }
    }

    /**
     * 
     * @param Block $block
     * @return Source
     */
    public function addBlock(Block $block)
    {
        $type                          = $block->getBlockType();
        $pointer                       = $block->getStartToken()->getPointer();
        $this->blocks[$type][$pointer] = $block;
        return $this;
    }

    /**
     * 
     * @param string|null $type
     * @return Block[]
     */
    public function getBlocks($type = null)
    {
        if (null === $type)
        {
            return $this->blocks;
        }
        else
        {
            return isset($this->blocks[$type]) ? $this->blocks[$type] : [];
        }
    }

    public function __toString()
    {
        return '';
    }

    public function getTokenName($tok)
    {
        if (is_int($tok))
        {
            return token_name($tok);
        }

        if (isset($this->undefinedTokens[$tok]))
        {
            return $this->undefinedTokens[$tok];
        }

        return static::UNKNOWN_TOKEN;
    }

    /**
     * 
     * @param Token $startToken
     * @param Block $parentBlock
     * @return Block|null
     */
    public function makeBlock(Token $startToken, Block $parentBlock = null)
    {
        $tokenName = $startToken->getToken();
        if (!isset($this->tokenHandlerCache[$tokenName]))
        {

            $class = __NAMESPACE__ . '\\Block\\'
                    . $startToken->getStudlyName() . 'Block';

            if (!class_exists($class))
            {
                $this->tokenHandlerCache[$tokenName] = false;
                return null;
            }

            $reflect = new \ReflectionClass($class);

            if ($reflect->implementsInterface(__NAMESPACE__ . '\\Pattern\\InterfaceBlockHandler'))
            {
                $this->tokenHandlerCache[$tokenName] = $class;
            }
            else
            {
                $this->tokenHandlerCache[$tokenName] = false;
            }
        }

        $class = $this->tokenHandlerCache[$tokenName];


        return empty($class) ? null : (new $class($this, $startToken, $parentBlock));
    }

    public function getBlockType()
    {
        return 'SOURCE';
    }

    /**
     * 
     * @param int $pointer
     * @return Token|null
     */
    public function getTokenAt($pointer)
    {
        return isset($this->tokens[$pointer]) ? $this->tokens[$pointer] : null;
    }

    public function insertTokenAt(Token $token, $pointer)
    {
        
    }

    /**
     * 
     * @param int $fromPointer
     * @param Token|string|int $match
     * @return Token
     */
    public function findNextMatchingFrom($fromPointer, $match, array $acceptOnlyInBetween = [])
    {
        for ($next = $fromPointer; $next <= $this->getEndPointer(); $next++)
        {
            $currentToken = $this->getTokenAt($next);

            if ($currentToken->is($match))
            {
                return $currentToken;
            }

            if (!empty($acceptOnlyInBetween)
                    && $next !== $fromPointer // has moved one or more
                    && !($currentToken->is($acceptOnlyInBetween)))
            {
                return $this->getTokenAt($fromPointer);
            }
        }
    }

    /**
     * 
     * @param int $fromPointer
     * @param Token|string|int $match
     * @return Token
     */
    public function findPreviousMatchingFrom($fromPointer, $match, array $acceptOnlyInBetween = [])
    {

        for ($next = $fromPointer; $next >= $this->getStartPointer(); $next--)
        {
            $currentToken = $this->getTokenAt($next);

            if ($currentToken->is($match))
            {
                return $currentToken;
            }

            if (!empty($acceptOnlyInBetween)
                    && $next !== $fromPointer // has moved one or more
                    && !($currentToken->is($acceptOnlyInBetween)))
            {
                return $this->getTokenAt($fromPointer);
            }
        }
    }

    public function getTokensFromTo($startPointer, $endPointer)
    {
        if (!isset($this->tokens[$start]) || !isset($this->tokens[$end]))
        {
            return array();
        }

        $ret = [];

        for ($i = $start; $i <= $endPointer; $i++)
        {
            if (isset($this->tokens[$i]))
            {
                $ret[$i] = $this->tokens[$i];
            }
        }

        return $ret;
    }

    /**
     * 
     * @return int
     */
    public function getStartPointer()
    {
        return $this->startPointer;
    }

    /**
     * 
     * @return int
     */
    public function getEndPointer()
    {
        return $this->endPointer;
    }

    /**
     * 
     * @param int $startPointer
     * @return static
     */
    protected function setStartPointer($startPointer)
    {
        $this->startPointer = $startPointer;
        return $this;
    }

    /**
     * 
     * @param int $endPointer
     * @return static
     */
    protected function setEndPointer($endPointer)
    {
        $this->endPointer = $endPointer;
        return $this;
    }

    public function tokensToString()
    {
        $ret = '';

        foreach ($this->tokens as $token)
        {
            $ret.= $token->__toString();
        }
        return $ret;
    }

}
