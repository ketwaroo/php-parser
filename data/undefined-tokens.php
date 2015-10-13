<?php

/**
 *  Undefined tokens
 *  @author Yaasir Ketwaroo <ketwaroo.yaasir@gmail.com>
 */
use Ketwaroo\PhpParser\Source;

define('KTOK_UNKNOWN', "\x00"); //for as of yet unsupported tokens.
define('KTOK_CURLY_OPEN', '{');
define('KTOK_CURLY_CLOSE', '}');
define('KTOK_SEMICOLON', ';');


Source::registerBlockHandlers([
    'T_REQUIRE'      => 'Require',
    'T_REQUIRE_ONCE' => 'Require',
    'T_INCLUDE'      => 'Require',
    'T_INCLUDE_ONCE' => 'Require',
]);

