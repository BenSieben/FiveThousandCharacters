<?php
namespace cs174\hw3\views;
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
    <ul>
        <li><a href="?mode=3">Read a Story Page</a></li>
    </ul>
    <h2><a href="?mode=2">Write Something!</a></h2> <!-- should take user to write something view -->
    <h2>Check out what people are writing...</h2>
    <!-- TODO form data should be sanitized -->
    <!-- TODO form data should be saved in a session for the user -->
    <form name="filterForm" method="get">
        <input type="text" name="textField" placeholder="Phrase Filter"/>
        <select name="genre" title="Genre Filter Selection">
            <option value="all">All Genres</option>
            <option>List of all unique DB Genres go below</option>
        </select>
        <input type="submit" value="Go"/>
    </form>
    <h3>Highest Rated</h3>
    <ol>
        <li>Top 10 highest rated stories go here</li>
    </ol>
    <h3>Most Viewed</h3>
    <ol>
        <li>Top 10 most viewed stories go here</li>
    </ol>
    <h3>Newest</h3>
    <ol>
        <li>Top 10 newest stories go here</li>
    </ol>
</body>
</html>
<?php
    }
}
?>
