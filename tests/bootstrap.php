<?php


declare(strict_types=1);

error_reporting(-1);

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_ENV', 'test');

$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require_once dirname(__DIR__) . '/vendor/autoload.php';
