<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($argv[1])) {
    die('not set uri');
}

define('CORE_ROOT', '../../');
define('SITE_ROOT', './');
define('DOCUMENT_URI', $argv[1]);
define('HTTP_HOST', 'cron');

include CORE_ROOT . 'core/Flame/autoloader.php';
include '../vendor/autoload.php';

$init = new \Flame\Classes\Init();

try {
    $init->initRoute('file', 'yaml', './conf/route.yaml');
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL, $ex->getTraceAsString();
}
