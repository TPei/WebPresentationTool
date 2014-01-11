<?php

use db\MongoAdapter;

session_start();
// automatically detect and include all php classes in src directory
spl_autoload_register(function ($class) {
    $class = strtr($class, '\\', '/');

    // going from this directory to src directory and include if file exists
    $file = __DIR__ . '/src/' . $class . '.php';
    if (file_exists($file)) {
        /** @noinspection PhpIncludeInspection */
        include($file);

    }
});

// enable displaying of errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

MongoAdapter::settings(array(
    MongoAdapter::DB_HOST => 'localhost',
    MongoAdapter::DB_NAME => 'webHaPeikert530022',
    MongoAdapter::RESOURCE_DB_NAME => 'webHaResourcesPeikert530022',
));