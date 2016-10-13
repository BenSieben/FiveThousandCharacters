<?php
/**
 * Landing page for Five Thousand Characters Website
 */

namespace cs174\hw3\views;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Five Thousand Characters</title>
    <meta charset="utf-8" />
    <link rel="icon" href="../resources/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="../styles/stylesheet.css" />
</head>
<body>
    <h1>Five Thousand Characters</h1>
    <h2><a href="../../index.php">Back to index.php</a></h2>
    <ul>
        <li><a href="Read.php">Read a Story Page</a></li>
    </ul>
    <h2><a href="Write.php">Write Something!</a></h2> <!-- should take user to write something view -->
    <h2>Check out what people are writing...</h2>
    <!-- TODO form data should be sanitized -->
    <!-- TODO form data should be saved in a session for the user -->
    <form name="form" method="get">
        <input type="text" name="textField" placeholder="Phrase Filter"/>
        <select name="genre" title="Genre Filter Selection">
            <option value="all">All Genres</option>
            <option>List of all unique DB Genres go below</option>
        </select>
        <input type="submit" value="Go" />
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
