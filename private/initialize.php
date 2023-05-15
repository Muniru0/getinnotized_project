<?php

// define constants for the various directories




// Perform all initialization here, in private

// Set constants to easily reference public 
// and private directories
define("APP_ROOT", dirname(dirname(__FILE__)));
define("PRIVATE_PATH", APP_ROOT . "/private");
define("PUBLIC_PATH", APP_ROOT . "/public");

// Define the core paths
// Define them as absolute paths to make sure that require_once works
// as expected.

// DIRECTORY_SEPARATOR is a  PHP pre-defined constant
// (\ for windows,/ for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR,false);

defined('PUBLIC_ROOT') ? null:
define ('PUBLIC_ROOT','complete_project'.DS.'public');

defined('PRIVATE_ROOT') ? null:
define ('PRIVATE_ROOT','complete_project'.DS.'public');

// defined('BASE_URL') ? null:
// define ('BASE_URL','http://localhost/getinnotized_project');
defined('BASE_URL') ? null:
define ('BASE_URL','http://localhost:8080/getinnotized_project');


//to be defined later
defined('LIB_PATH') ? null : define('LIB_PATH',PUBLIC_ROOT.DS.'includes');

session_start();

 require_once(PRIVATE_PATH . "/functions/general_functions.php");
 require_once(PRIVATE_PATH . "/functions/config.php");
 require_once(PRIVATE_PATH . "/functions/session_hijacking_functions.php");
 require_once(PRIVATE_PATH . "/classes/class.database_object.php");
 require_once(PRIVATE_PATH . "/functions/csrf_request_type_functions.php");
 require_once(PRIVATE_PATH . "/functions/csrf_token_functions.php");
 require_once(PRIVATE_PATH . "/functions/request_forgery_functions.php");
 require_once(PRIVATE_PATH . "/classes/class.phonebook.php");


 // NOTE: In a real world application all these files will be uncommented for use.
 
// require_once(PRIVATE_PATH . "/throttle.php");
// require_once(PRIVATE_PATH . "/functions/general_functions.php");
// require_once(PRIVATE_PATH . "/functions/config.php");
// require_once(PRIVATE_PATH . "/functions/database_object.php");
// require_once(PRIVATE_PATH . "/functions/blacklist_functions.php");
// require_once(PRIVATE_PATH . "/functions/csrf_request_type_functions.php");
// require_once(PRIVATE_PATH . "/functions/csrf_token_functions.php");
// require_once(PRIVATE_PATH . "/functions/request_forgery_functions.php");
// require_once(PRIVATE_PATH . "/functions/reset_token_functions.php");
// require_once(PRIVATE_PATH . "/functions/session_hijacking_functions.php");
// require_once(PRIVATE_PATH . "/functions/sqli_escape_functions.php");
// require_once(PRIVATE_PATH . "/functions/throttle_functions.php");
// require_once(PRIVATE_PATH . "/functions/database.php");
// require_once(PRIVATE_PATH . "/functions/xss_sanitize_functions.php");
// require_once(PRIVATE_PATH . "/user.php");
// require_once(PRIVATE_PATH . "/file_uploads.php");




?>
