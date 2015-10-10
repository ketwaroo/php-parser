<?php

/*
 *  @author Yaasir Ketwaroo <ketwaroo.yaasir@gmail.com>
 */

namespace Ketwaroo\PhpParser\Pattern;

/**
 *
 * @author Administrator
 */
interface InterfaceConstants
{
    /**
     * built in token constants will have this prefix
     */
    const DEFINED_TOKENS_CONSTANT_PREFIX = 'T_';

    /**
     * constants we've defined will have this prefix.
     */
    const UNDEFINED_TOKENS_CONSTANT_PREFIX = 'KTOK_';

    const UNKNOWN_TOKEN = 'KTOK_UNKNOWN';
}
