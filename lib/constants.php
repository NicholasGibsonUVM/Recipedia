<?php 
define('DEBUG', false);

$_SERVER = filter_input_array(INPUT_SERVER, FILTER_SANITIZE_STRING);

define('SERVER', $_SERVER['SERVER_NAME']);

define('DOMAIN' , '//' . SERVER);

define('PHP_SELF', $_SERVER['PHP_SELF']);

define('PATH_PARTS', pathinfo(PHP_SELF));

define('BASE_PATH', DOMAIN . PATH_PARTS['dirname'] . '/');

define('LIB_PATH', 'lib/');

define('DATABASE_NAME', 'NSGIBSON_cs148_lab4');

if (DEBUG) {
    print '<p>Domain ' . DOMAIN;
    print '<p>PHP SELF: ' . PHP_SELF;
    print '<p>PATH PARTS<pre>';
    print_r(PATH_PARTS);
    print '</pre></p>';
    print '<p>BASE_PATH: ' . BASE_PATH;
    print '<p>LIB_PATH: ' . LIB_PATH;
}
