<?php
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

$mode = 1;
if(isset($_REQUEST['mode'])) {
    $mode = intval($_REQUEST['mode']);
}
if($mode === 1) {
    $landing = new cs174\hw3\views\Landing();
    $landing->render(null);
}
else if($mode === 2){
    $landing = new cs174\hw3\views\Write();
    $landing->render(null);
}
else if($mode === 3){
    $landing = new cs174\hw3\views\Read();
    $landing->render(null);
}

$m = new cs174\hw3\models\GenreModel();
$result = $m->getListOfGenres();
print_r(mysqli_fetch_all($result));

?>