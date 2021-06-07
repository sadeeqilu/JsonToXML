
<?php

if ((!defined('CONST_INCLUDE_KEY')) || (CONST_INCLUDE_KEY !== 'd4e2ad09-b1c3-4d70-9a9a-0e6149302486')) {
	// If someone tries to browse directly to this PHP file, send error and exit. It can only included
	// as part of our API.
	// header("Location: /404.html", TRUE, 404);
    // echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/404.html');
    echo "Error";
	die;
}

//----------------------------------------------------------------------------------------------------------------------
// Build the class mapping array
$mapping = [
   
    // app classes
    // 'API_Handler' => './api_handler.php',
    'App_Response' => './app_response.php',
    // 'JWT' => './src/app_jwt.php',

    // database classes
    // 'Data_Access' => './database/data_access.php',
    // 'App_API_Key' => './database/app_api_key.php'
    
 ];

//----------------------------------------------------------------------------------------------------------------------
spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require_once $mapping[$class];
    }
}, true);