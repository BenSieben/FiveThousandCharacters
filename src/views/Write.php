<?php
namespace cs174\hw3\views;
use cs174\hw3\views\helpers\SelectOptionHelper;
use cs174\hw3\views\helpers\WriteErrorHelper;

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
    <h1><a href="?c=LandingController&m=processForms">Five Thousand Characters</a> - Write Something</h1>
<?php
        // render all error messages that user caused (if they exist)
        $writeErrorHelper = new WriteErrorHelper();
        echo($writeErrorHelper->render($data['errorMessages']));
    ?>
    <form name="writeForm" method="post" action="?c=WriteSubmitController&m=processForms">
        <label>Title</label>
        <br />
        <input type="text" name="writeTitle" value="<?= $data['writeTitle'] ?>" />
        <br />
        <label>Author</label>
        <br />
        <input type="text" name="writeAuthor" value="<?= $data['writeAuthor'] ?>" />
        <br />
        <label>Identifier</label>
        <br />
        <input type="text" name="writeIdentifier" value="<?= $data['writeIdentifier'] ?>" />
        <br />
        <label>Genre(s)</label>
        <br />
        <select name="writeGenres[]" title="Genre Filter Selection" multiple="multiple" size="10">
<?php
        // render all genre options in the select drop down
        $selectOptionHelper = new SelectOptionHelper($data['writeGenres']);
        echo($selectOptionHelper->render($data['genreList']));
            ?>
        </select>
        <br />
        <textarea name="writeStory" rows="50" cols="100"
                  placeholder="Write your story here!"><?= $data['writeStory'] ?></textarea>
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