<?php

require_once "models/modelFramework.php";
require_once "models/modelHelpers.php";

// Initialize the framework

// Set the time zone
date_default_timezone_set("UTC");

// Required for the error handler
define("SERVER_NAME",  $_SERVER["SERVER_NAME"]);
define("IS_LOCALHOST", SERVER_NAME == "localhost");

// Set our own error handler to log errors to the system logger
set_error_handler("framework\\errorHandler");

// Register the module auto loader
spl_autoload_register("framework\\autoloader");

// Initialize the session
session_start();

// Get protocol for the web root define
$protocol = framework\getProtocol();

define("LOG_TIME_FORMAT",       "Y-m-d H:i:s");
define("MAX_LOG_LINES",         1000);

// Paths
$webFolder = trim(dirname($_SERVER["PHP_SELF"]), "\\/");
if ($webFolder != "")
{
    $webFolder .= "/";
}
define("FRAMEWORK_FOLDER",      rtrim(str_replace("\\", "/", __DIR__), "\\/") . "/");
define("SERVER_ROOT",           rtrim($_SERVER["DOCUMENT_ROOT"], "\\/") . "/");
define("WEB_FOLDER",            $webFolder);
define("ABS_PATH",              SERVER_ROOT . WEB_FOLDER);
define("LINK_ROOT",             $protocol . "://" . $_SERVER["HTTP_HOST"] . "/" . WEB_FOLDER);
define("LINK_ROOT_SSL",         "https://" . $_SERVER["HTTP_HOST"] . "/" . WEB_FOLDER);
define("REQUEST_URI",           substr(trim($_SERVER["REQUEST_URI"], "/"), strlen(WEB_FOLDER)));
define("PAGE_URI",              explode("?", REQUEST_URI)[0]);

// Log folder
if (defined("APP_LOG_FOLDER"))
{
    define("LOG_FOLDER", ABS_PATH . APP_LOG_FOLDER);
}
else
{
    define("LOG_FOLDER", FRAMEWORK_FOLDER . ".logs/");
}

// Framework search paths
define("FRAMEWORK_SEARCH_PATHS", [
    FRAMEWORK_FOLDER . "controllers",
    FRAMEWORK_FOLDER . "models",
    FRAMEWORK_FOLDER . "views",
]);

// Application search paths
define("APPLICATION_SEARCH_PATHS", (defined("SEARCH_PATHS") ?
    array_map(function($x) { return SERVER_ROOT . WEB_FOLDER . $x; }, SEARCH_PATHS) : []));

// Framework logger
define("FRAMEWORK_LOG", new framework\ModelLogger("framework"));
FRAMEWORK_LOG->writeMessage("----------------------------------- Framework start -----------------------------------");
FRAMEWORK_LOG->writeMessage("LOG_TIME_FORMAT         : '" . LOG_TIME_FORMAT . "'");
FRAMEWORK_LOG->writeMessage("MAX_LOG_LINES           : " . MAX_LOG_LINES);
FRAMEWORK_LOG->writeMessage("SERVER_NAME             : '" . SERVER_NAME . "'");
FRAMEWORK_LOG->writeMessage("IS_LOCALHOST            : " . var_export(IS_LOCALHOST, true));
FRAMEWORK_LOG->writeMessage("FRAMEWORK_FOLDER        : '" . FRAMEWORK_FOLDER . "'");
FRAMEWORK_LOG->writeMessage("LOG_FOLDER              : '" . LOG_FOLDER . "'");
FRAMEWORK_LOG->writeMessage("SERVER_ROOT             : '" . SERVER_ROOT . "'");
FRAMEWORK_LOG->writeMessage("WEB_FOLDER              : '" . WEB_FOLDER . "'");
FRAMEWORK_LOG->writeMessage("ABS_PATH                : '" . ABS_PATH . "'");
FRAMEWORK_LOG->writeMessage("LINK_ROOT               : '" . LINK_ROOT . "'");
FRAMEWORK_LOG->writeMessage("LINK_ROOT_SSL           : '" . LINK_ROOT_SSL . "'");
FRAMEWORK_LOG->writeMessage("REQUEST_URI             : '" . REQUEST_URI . "'");
FRAMEWORK_LOG->writeMessage("FRAMEWORK_SEARCH_PATHS  :");
FRAMEWORK_LOG->writeDataArray(FRAMEWORK_SEARCH_PATHS);
FRAMEWORK_LOG->writeMessage("APPLICATION_SEARCH_PATHS:");
FRAMEWORK_LOG->writeDataArray(APPLICATION_SEARCH_PATHS);
