<?php

require_once "models/modelFramework.php";

// Initialize the framework

// Set the time zone
date_default_timezone_set("UTC");

// Register the module auto loader
spl_autoload_register("framework\\autoloader");

// Initialize the session
session_start();

define("FRAMEWORK_FOLDER", __DIR__);
