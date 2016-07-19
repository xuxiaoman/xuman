<?php
if (!is_file(dirname(__DIR__)."/BIN.LOCK")) {
    header("Location:" . dirname(dirname($_SERVER['SCRIPT_NAME'])). "/Install/index.php");
    exit;
}
require_once "..".DIRECTORY_SEPARATOR.'Core'.DIRECTORY_SEPARATOR.'init.php';
require_once "..".DIRECTORY_SEPARATOR.'Core'.DIRECTORY_SEPARATOR.'core.php';
?>