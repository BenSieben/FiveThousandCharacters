<?php
namespace cs174\hw3\views;
use Config;
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
    <form name="writeForm" method="post">
        <label>Title</label>
        <br />
        <input type="text" name="title" maxlength="<?= Config::WS_MAX_TITLE_LENGTH ?>" />
        <br />
        <label>Author</label>
        <br />
        <input type="text" name="author" maxlength="<?= Config::WS_MAX_AUTHOR_LENGTH ?>" />
        <br />
        <label>Identifier</label>
        <br />
        <input type="text" name="identifier" maxlength="<?= Config::WS_MAX_IDENTIFIER_LENGTH ?>" />
        <br />
        <label>Genre</label>
        <br />
        <select name="genre" title="Genre Filter Selection" multiple="multiple">
            <option>List of all unique DB Genres go here, and multiple can be selected</option>
            <option>Other genres listed here</option>
        </select>
        <br />
        <textarea name="story" maxlength="<?= Config::WS_MAX_STORY_LENGTH ?>"
                  rows="50" cols="100" placeholder="Write your story here!"></textarea>
        <br />
        <input type="reset" />
        <input type="submit" value="Save"/>
    </form>
</body>
</html>
<?php
    }
}
?>