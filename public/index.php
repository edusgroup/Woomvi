<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use \Flame\Classes\Http\Response\Json;

if (!isset($_SERVER['CORE_ROOT'])) {
    die('set in Nginx conf CORE_ROOT');
}

if (!isset($_SERVER['SITE_ROOT'])) {
    die('set in Nginx conf SITE_ROOT');
}

define('CORE_ROOT', $_SERVER['CORE_ROOT']);
define('SITE_ROOT', $_SERVER['SITE_ROOT']);
define('DOCUMENT_URI', $_SERVER['DOCUMENT_URI']);
define('HTTP_HOST', $_SERVER['HTTP_HOST']);

include CORE_ROOT . 'core/Flame/autoloader.php';
include SITE_ROOT . 'vendor/autoload.php';

$init = new \Flame\Classes\Init();

try {
    $data = $init->initRoute('file', 'yaml', SITE_ROOT . 'conf/route.yaml');
    if ($data instanceof \Flame\Classes\Http\Response\Json) {
        header('Content-Type: application/json; charset=utf-8');
        echo $data->get();
        exit;
    }

    if ($data instanceof \Flame\Classes\Http\Response\String) {
        header('Content-Type: text/html; charset=utf-8');
        echo $data->get();
        exit;
    }

    if ($data instanceof \Flame\Classes\Http\Response\Html) {
        header('Content-Type: text/html; charset=utf-8');
        echo $data->get();
        exit;
    }

    if ($data instanceof \Flame\Classes\Http\Response\FileHandle) {
        $contentType = $data->getContentType();
        header('Content-Type: ' . $contentType);
        $fr = $data->get();
        if (fpassthru($fr)) {
            fclose($fr);
        }
        exit;
    }
} catch (\Flame\Classes\Http\Exception\Error4xx $ex) {
    header('HTTP/1.0 404 Not Found');
    header('Content-Type: text/html; charset=utf-8');
    echo '<pre>' . $ex->getMessage() . PHP_EOL . $ex->getTraceAsString() . '</pre>';
    exit;
} catch (\Exception $ex) {
    if ($init->isAjaxRequest()) {
        echo (new Json('', Json::STATUS_ERROR, $ex->getMessage()))->get();
        exit;
    }
    echo '<pre>' . $ex->getMessage() . PHP_EOL . $ex->getTraceAsString() . '</pre>';
}
