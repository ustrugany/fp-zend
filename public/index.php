<?php
// poprawka adresow dla hostingu bez mod_rewrite
if(($_SERVER["SERVER_ADDR"] == "85.199.177.117") && ($_SERVER["SERVER_NAME"] == "aplikacja.babybuu.de")){
    $_SERVER["REQUEST_URI"] = str_replace('index.php','',$_SERVER["REQUEST_URI"]);
}
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
	'/var/www/s17u67/html/aplikacja/library/ZendFramework-1.11.13/library',
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
            ->run();