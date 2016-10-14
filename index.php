<?php
require_once("src/views/View.php");
require_once("src/views/Landing.php");
require_once("src/views/Read.php");
require_once("src/views/Write.php");
$mode = 1;
if($mode === 1) {
    $landing = new cs174\hw3\views\Landing();
    $landing->render(null);
}
?>