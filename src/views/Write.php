<?php
namespace cs174\hw3\views;
use Config;
use cs174\hw3\views\helpers\SelectOptionHelper;

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
    <form name="writeForm" method="post" action="?c=WriteSubmitController&m=processForms">
        <label>Title</label>
        <br />
        <!-- Due to how the database is configured, if given data that passes max length specified in Config,
         excess data gets truncated from database entry (no error message or anything). This is why I have elected to specify
         the maxLength of all the fields on the write something view as the check for lengths of user input -->
        <input type="text" name="writeTitle" value="<?= $data['writeTitle'] ?>" maxlength="<?= $data['maxTitleLength'] ?>" />
        <br />
        <label>Author</label>
        <br />
        <input type="text" name="writeAuthor" value="<?= $data['writeAuthor'] ?>" maxlength="<?= $data['maxAuthorLength'] ?>" />
        <br />
        <label>Identifier</label>
        <br />
        <input type="text" name="writeIdentifier" value="<?= $data['writeIdentifier'] ?>" maxlength="<?= $data['maxIdentifierLength'] ?>" />
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
        <textarea name="writeStory" maxlength="<?= $data['maxStoryLength'] ?>"
                  rows="50" cols="100" placeholder="Write your story here!"><?= $data['writeStory'] ?></textarea>
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