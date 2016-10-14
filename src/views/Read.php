<?php
namespace cs174\hw3\views;
/**
 * Class Read
 * @package cs174\hw3\views
 *
 * The View that displays the Read Story page
 */

class Read extends View {
    function render($data) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Five Thousand Characters - Story Title</title>
    <meta charset="utf-8" />
    <link rel="icon" href="../resources/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="../styles/stylesheet.css" />
</head>
<body>
    <h1><a href="Landing.php">Five Thousand Characters</a> - Story Title</h1>
    <div>
        <b>Author</b>: author goes here
        <br />
        <b>First published</b>: date of when story was first saved goes here
        <br />
        <b>Your rating</b>: 1 2 3 4 5
        <br />
        <b>Average rating</b>: average rating for the story goes here
    </div>
    <p>Story goes here, in new paragraphs for when original text has two consecutive new lines</p>
</body>
</html>
<?php
    }
}
?>