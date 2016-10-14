<?php
namespace cs174\hw3\views;
/**
 * Class Write
 * @package cs174\hw3\views
 *
 * The View that displays the Write Something page
 */

class Write extends View {
    function render($data){
?>
<!DOCTYPE html>
<html>
<head>
    <title>Five Thousand Characters - Write Something</title>
    <meta charset="utf-8" />
    <link rel="icon" href="src/resources/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="src/styles/stylesheet.css" />
</head>
<body>
    <h1><a href="?mode=1">Five Thousand Characters</a> - Write Something</h1>
</body>
</html>
<?php
    }
}
?>