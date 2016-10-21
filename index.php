<?php
// start a new session for the user
session_start();

// import all the PHP classes for the website
require_once("src/configs/Config.php"); // no namespace for Config.php, so autoload will not work for this class
spl_autoload_register(function ($className) {
    // all files are in src folder, which makes it the prefix
    $prefix = 'src/';

    // then directory of class (since all classes in this website have namespaces
    //   cs174\hw3\...  in folders named ...) we do some string manipulation
    //   to extract the proper directory to jump to for requiring the class
    //   (replacing backslash with forward slash, and only include name of class
    //   starting after cs175\hw3\ in namespace to get file directory of the class)
    $dir = str_replace('\\', '/', substr($className, strpos($className, "\\hw3\\") + 5)) . '.php';

    // combine prefix and directory to pick out file to require
    if(file_exists("$prefix$dir")) {
        require_once("$prefix$dir");
    }
});

// Make a new Controller and let it determine what to do
//   based on current values in PHP super globals
$controller = new \cs174\hw3\controllers\Controller();
$controller->processForms();

// end the session for the user
// TODO remove this line when testing sessions (and ready to release final product)
// session_destroy();
?>