<?php
namespace cs174\hw3\views;
use cs174\hw3\views\helpers\StoryContentsHelper;

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
    <title>Five Thousand Characters - <?= $data['title'] ?></title>
    <meta charset="utf-8" />
    <link rel="icon" href="src/resources/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="src/styles/stylesheet.css" />
</head>
<body>
    <h1><a href="?c=LandingController&?m=processForms">Five Thousand Characters</a> - <?= $data['title'] ?></h1>
    <div>
        <b>Author</b>: <?= $data['author'] ?>
        <br />
        <b>First published</b>: <?= $data['submitTime'] ?>
        <br />
        <b>Your rating</b>: <?php
        // depending on whether user has submitted a rating or not, we will show different things
        if(strcmp($data['userRating'], '0') === 0) {
            // if userRating is '0', then they have not rated yet, so give them links to rate the story
            $sID = $data['sID'];
            echo("<a href=\"?c=ReadController&m=processForms&sID=$sID&rating=1\">1</a>  " .
                "<a href=\"?c=ReadController&m=processForms&sID=$sID&rating=2\">2</a>  " .
                "<a href=\"?c=ReadController&m=processForms&sID=$sID&rating=3\">3</a>  " .
                "<a href=\"?c=ReadController&m=processForms&sID=$sID&rating=4\">4</a>  " .
                "<a href=\"?c=ReadController&m=processForms&sID=$sID&rating=5\">5</a>");
        }
        else {
            // if userRating is not '0', then they have rated already, so give them bold to show their rating
            //   but do not let them pick a rating again
            switch($data['userRating']) {
                case "1":
                    echo("<b>1</b>  2  3  4  5");
                    break;
                case "2":
                    echo("1  <b>2</b>  3  4  5");
                    break;
                case "3":
                    echo("1  2  <b>3</b>  4  5");
                    break;
                case "4":
                    echo("1  2  3  <b>4</b>  5");
                    break;
                case "5":
                    echo("1  2  3  4  <b>5</b>");
                    break;
            }
        }
        ?>
        <br />
        <b>Average rating</b>: <?= $data['avgRating'] ?>
    </div>
    <br />
    <br />
    <br />
<?php
        // must show the content of the story in paragraphs, which is done by StoryContentsHelper
        $storyContentsHelper = new StoryContentsHelper();
        echo($storyContentsHelper->render($data['content']));
    ?>
</body>
</html>
<?php
    }
}
?>