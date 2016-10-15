<?php
require_once("Config.php");
/**
 * PHP script which utilizes the Config class
 * in order to connect to a DBMS and create a
 * new database which will be used to store
 * all information for the website (also creates
 * some starter data to initialize the database)
 */

// first attempt to establish a connection to the database
$db = mysqli_connect(Config::DB_HOST, Config::DB_USERNAME, Config::DB_PASSWORD, "", Config::DB_PORT);
if(!$db) {
    echo("Error connecting to \"" . Config::DB_HOST . "\" with username \"" . Config::DB_USERNAME
        . "\", password \"" . Config::DB_PASSWORD . "\", and port \"" . Config::DB_PORT . "\".");
    return;
}

// if connection worked, create and initialize the database
echo("Connection success, now setting up database " . Config::DB_DATABASE . " (any existing " .
    "database with this name will be deleted completely)\n");

// create the database and use it
mysqli_query($db, "DROP DATABASE IF EXISTS " . Config::DB_DATABASE);
mysqli_query($db, "CREATE DATABASE " . Config::DB_DATABASE);
mysqli_query($db, "USE " . Config::DB_DATABASE);

// create the Story relation, which contains most information for each written story
mysqli_query($db, "DROP TABLE IF EXISTS Story");
mysqli_query($db, "CREATE TABLE Story(sID VARCHAR(" . Config::WS_MAX_IDENTIFIER_LENGTH . "), " .
                                     "title VARCHAR(" . Config::WS_MAX_TITLE_LENGTH . ") NOT NULL, " .
                                     "author VARCHAR(" . Config::WS_MAX_AUTHOR_LENGTH . ") NOT NULL, " .
                                     "submitTime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
                                     "content VARCHAR(" . Config::WS_MAX_STORY_LENGTH . ") NOT NULL, " .
                                     "views INT NOT NULL DEFAULT 0, " .
                                     "ratingsSum INT NOT NULL DEFAULT 0, " .
                                     "ratingsCount INT NOT NULL DEFAULT 0, " .
                                     "PRIMARY KEY (sID))");

// create the Genre relation, which contains a list of possible genres that can be associated with stories
mysqli_query($db, "DROP TABLE IF EXISTS Genre");
mysqli_query($db, "CREATE TABLE Genre(gID INT AUTO_INCREMENT, " .
                                     "name VARCHAR(" . Config::MAX_GENRE_NAME_LENGTH . ") NOT NULL, " .
                                     "PRIMARY KEY (gID))");

// create the StoryGenres relation, which contains a list of story ID's and genre ID's to tell the genres of stories
mysqli_query($db, "DROP TABLE IF EXISTS StoryGenres");
mysqli_query($db, "CREATE TABLE StoryGenres(sID VARCHAR(" . Config::WS_MAX_IDENTIFIER_LENGTH . "), " .
                                          "gID INT, " .
                                          "PRIMARY KEY (sID, gID), " .
                                          "FOREIGN KEY (sID) REFERENCES Story(sID), " .
                                          "FOREIGN KEY (gID) REFERENCES Genre(gID))");

// load up all possible genres into the Genre relation
mysqli_query($db, "INSERT INTO Genre(name) VALUES('Action'), " .
                                                "('Adventure'), " .
                                                "('Comedy'), " .
                                                "('Crime'), " .
                                                "('Drama'), " .
                                                "('Fantasy'), " .
                                                "('Historical'), " .
                                                "('Horror'), " .
                                                "('Mystery'), " .
                                                "('Philosophical'), " .
                                                "('Romance'), " .
                                                "('Satire'), " .
                                                "('Science Fiction'), " .
                                                "('Thriller'), " .
                                                "('Western')");

// load up some sample stories
/*mysqli_query($db, "INSERT INTO Story(sID, title, author, submitTime, content) " .
    "VALUES('sample1', 'Winter Frost', 'Ace Brown', '2000-01-01 01:30:26', 'Winter is cold. I feel frosty...') " .
")");*/

?>