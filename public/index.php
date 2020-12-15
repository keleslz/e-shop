<?php
ini_set( 'display_errors', 1);

define('ROOT', dirname(__DIR__));
define('DS', DIRECTORY_SEPARATOR);
require_once ROOT . DS . 'core/includes.php';
require_once ROOT . DS . 'src/app-includes.php'; // Includes all class required by app
new CallController();