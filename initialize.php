<?php

require_once "models/modelFramework.php";

// Initialize the framework

// Set the time zone
date_default_timezone_set("UTC");

// Register the module auto loader
spl_autoload_register("framework\\autoloader");

// Initialize the session
session_start();

define("FRAMEWORK_FOLDER",      __DIR__);
define("FRAMEWORK_LOG_FOLDER",  __DIR__ . "/.logs/");
define("LOG_TIME_FORMAT",       "Y-m-d H:i:s");
define("MAX_LOG_LINES",         1000);

define("FRAMEWORK_LOG",         new framework\ModelLogger("framework"));

FRAMEWORK_LOG->writeMessage("----------------------------------- Framework start -----------------------------------");
FRAMEWORK_LOG->writeMessage("FRAMEWORK_FOLDER     : '" . FRAMEWORK_FOLDER . "'");
FRAMEWORK_LOG->writeMessage("FRAMEWORK_LOG_FOLDER : '" . FRAMEWORK_LOG_FOLDER . "'");
FRAMEWORK_LOG->writeMessage("LOG_TIME_FORMAT      : '" . LOG_TIME_FORMAT . "'");
FRAMEWORK_LOG->writeMessage("MAX_LOG_LINES        : " . MAX_LOG_LINES);
