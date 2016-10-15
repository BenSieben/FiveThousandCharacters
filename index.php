<?php
// import all the PHP classes for the website
require_once("src/configs/Config.php");
require_once("src/models/Model.php");
require_once("src/views/View.php");

require_once("src/models/RatingAdderModel.php");
require_once("src/models/TopTenModel.php");
require_once("src/views/Landing.php");
require_once("src/views/Read.php");
require_once("src/views/Write.php");

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

?>