<?php
require_once("src/views/View.php");
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