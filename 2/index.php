<?php

if (!is_file("BIN.LOCK")) {
    $SCRIPT_NAME = dirname($_SERVER['SCRIPT_NAME']);
    if ("/" == $SCRIPT_NAME)
        $SCRIPT_NAME = "";
    header("Location:" . $SCRIPT_NAME . "/Install/index.php");
    exit;
}
define("APP_NAME", "web");
define("APP_DEBUG", true);
define("APP_PATH", "./web/");
require_once "." . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'init.php';
require_once "." . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'core.php';
