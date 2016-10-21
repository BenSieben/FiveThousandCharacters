<?php
namespace cs174\hw3\views;
use cs174\hw3\views\elements\H3TitledOrderedListElement;
use cs174\hw3\views\helpers\SelectOptionHelper;

/**
 * Class Landing
 * @package cs174\hw3\views
 *
 * The View that displays the Landing page
 */
class Landing extends View {

    /**
     * Uses HTML to draw the landing page for the Five Thousand
     * Characters website
     * @param $data Array<String> array of data to show in the view
     * (ex: filters, pre-filled forms, etc.)
     */
    function render($data) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Five Thousand Characters</title>
    <meta charset="utf-8"/>
    <link rel="icon" href="src/resources/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="src/styles/stylesheet.css" />
</head>
<body>
    <h1>Five Thousand Characters</h1>
    <h2><a href="?c=WriteController&?m=processForms">Write Something!</a></h2>
    <h2>Check out what people are writing...</h2>
    <form name="filterForm" method="get">
        <input type="hidden" name="c" value="LandingController" />
        <input type="hidden" name="m" value="processForms" />
        <input type="text" name="phraseFilter" placeholder="Phrase Filter" value="<?= $data['phraseFilter'] ?>"/>
        <select name="genre" title="Genre Filter Selection">
<?php
            // render all genre options in the select drop down
            $selectOptionHelper = new SelectOptionHelper($data['genre']);
            echo($selectOptionHelper->render($data['genreList']));
            ?>
        </select>
        <input type="submit" value="Go"/>
    </form>
<?php
            // render all three top ten lists
            $h3TitledOrderedListElement = new H3TitledOrderedListElement($this);
            echo($h3TitledOrderedListElement->render("Highest Rated", $data['topTenRated']));
            echo($h3TitledOrderedListElement->render("Most Viewed", $data['topTenViewed']));
            echo($h3TitledOrderedListElement->render("Newest", $data['topTenNewest']));
    ?>
</body>
</html>
<?php
    }
}
?>
