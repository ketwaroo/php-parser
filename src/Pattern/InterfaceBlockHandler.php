<?php

/**
 *  @author Yaasir Ketwaroo <ketwaroo.yaasir@gmail.com>
 */

namespace Ketwaroo\PhpParser\Pattern;

use Ketwaroo\PhpParser\Token;
use Ketwaroo\PhpParser\Block;
use Ketwaroo\PhpParser\Source;

/**
 * Description of InterfaceBlock
 */
interface InterfaceBlockHandler
{

    public function __toString();

    /**
     * @return Source
     */
    public function getSource();

    /**
     * @return Block
     */
    public function getParentBlock();

    /**
     * @return Token
     */
    public function getBlockOpener();

    /**
     * @return Token
     */
    public function getBlockCloser();
}
