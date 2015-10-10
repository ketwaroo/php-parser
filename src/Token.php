<?php

/**
 *  @author Yaasir Ketwaroo <ketwaroo.yaasir@gmail.com>
 */

namespace Ketwaroo\PhpParser;

use Ketwaroo\PhpParser\BlockBuilder;
use Ketwaroo\PhpParser\Pattern\InterfaceConstants;
/**
 * Description of Token
 */
class Token implements InterfaceConstants
{

    use \Ketwaroo\PhpParser\Pattern\TraitUtil;

    protected $token, $value, $line, $pointer;

    protected $tokenConstant;

    protected $tokenStudlyName = null;

    public function __construct($token, $value, $line = null, $pointer = null)
    {
        $this->setToken($token)
                ->setValue($value)
                ->setLine($line)
                ->setPointer($pointer);
    }

    public function getPointer()
    {
        return $this->pointer;
    }

    public function setPointer($pointer)
    {
        $this->pointer = $pointer;
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setLine($line)
    {
        $this->line = $line;
        return $this;
    }

    /**
     * 
     * @param string[]|int[] $tokenConstant Array or single constant value.
     * @return boolean
     */
    public function is($tokenConstant)
    {
        if (!is_array($tokenConstant))
        {
            $tokenConstant = [$tokenConstant];
        }
        array_walk($tokenConstant, function(&$v) {
            if ($v instanceof Token)
            {
                $v = $v->getTokenConstantValue();
            }
        });

        $needle = $this->getTokenConstantValue();

        return in_array($needle, $tokenConstant, true);
    }

    public function getTokenConstantValue()
    {
        return constant($this->token);
    }

    /**
     * 
     * @return int
     */
    public function countLines()
    {
        return $this->utilCountLines($this->getValue());
    }

    public function getStudlyName()
    {
        if (is_null($this->tokenStudlyName))
        {

            $prefixes = implode('|', [
                static::DEFINED_TOKENS_CONSTANT_PREFIX,
                static::UNDEFINED_TOKENS_CONSTANT_PREFIX
            ]);

            $rep = [
                '~^(?:' . $prefixes . ')~' => '',
                '~_~'                      => ' ',
            ];

            $this->tokenStudlyName = str_replace(' ', '', ucwords(strtolower(
                                    preg_replace(array_keys($rep), array_values($rep), $this->getToken())
            )));
        }
        return $this->tokenStudlyName;
    }

    public function __toString()
    {
        return $this->getValue();
    }

}
