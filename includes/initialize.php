<?php

// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// {\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', 'C:'.DS.'wamp'.DS.'www'.DS.'mdk.im'); // [CHANGEME]
defined('INC_PATH') ? null : define('INC_PATH', SITE_ROOT.DS.'includes');
defined('CLASS_PATH') ? null : define('CLASS_PATH', INC_PATH.DS.'classes');

// load config file first
require_once(INC_PATH.DS."constants.php");

// load basic functions next so that everything after can use them
require_once(INC_PATH.DS."functions.php");

//load core objects
require_once(INC_PATH.DS."classes".DS."class_mysqldatabase.php");
require_once(INC_PATH.DS."classes".DS."class_databaseobject.php");

// load database-related classes
require_once(INC_PATH.DS."classes".DS."class_shortener.php");


?>