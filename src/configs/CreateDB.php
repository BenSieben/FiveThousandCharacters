<?php
require_once("Config.php");

/**
 * PHP script which utilizes the Config class
 * in order to connect to a DBMS and create a
 * new database which will be used to store
 * all information for the website (also creates
 * some starter data to initialize the database with)
 */

// first attempt to establish a connection to the database
$mysqli = new mysqli(Config::DB_HOST, Config::DB_USERNAME, Config::DB_PASSWORD, "", Config::DB_PORT);
if($mysqli->connect_errno) {
    echo("Error connecting to \"" . Config::DB_HOST . "\" with username \"" . Config::DB_USERNAME
        . "\", password \"" . Config::DB_PASSWORD . "\", and port \"" . Config::DB_PORT . "\".");
    return;
}

// if connection worked, create and initialize the database
echo("Connection success, now setting up database " . Config::DB_DATABASE . " (any existing database with " .
    "this name will be deleted completely, and some initial sample data will be loaded into the database)\n");

// create the database and use it
$mysqli->query("DROP DATABASE IF EXISTS " . Config::DB_DATABASE);
$mysqli->query("CREATE DATABASE " . Config::DB_DATABASE);
$mysqli->query("USE " . Config::DB_DATABASE);

// create the Story relation, which contains most information for each written story
$mysqli->query("DROP TABLE IF EXISTS Story");
$mysqli->query("CREATE TABLE Story(sID VARCHAR(" . Config::WS_MAX_IDENTIFIER_LENGTH . "), " .
                                     "title VARCHAR(" . Config::WS_MAX_TITLE_LENGTH . ") NOT NULL, " .
                                     "author VARCHAR(" . Config::WS_MAX_AUTHOR_LENGTH . ") NOT NULL, " .
                                     "submitTime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
                                     "content VARCHAR(" . Config::WS_MAX_STORY_LENGTH . ") NOT NULL, " .
                                     "views INT NOT NULL DEFAULT 0, " .
                                     "ratingsSum INT NOT NULL DEFAULT 0, " .
                                     "ratingsCount INT NOT NULL DEFAULT 0, " .
                                     "PRIMARY KEY (sID))");

// create the Genre relation, which contains a list of possible genres that can be associated with stories
$mysqli->query("DROP TABLE IF EXISTS Genre");
$mysqli->query("CREATE TABLE Genre(gID INT AUTO_INCREMENT, " .
                                     "name VARCHAR(" . Config::MAX_GENRE_NAME_LENGTH . ") NOT NULL, " .
                                     "PRIMARY KEY (gID))");

// create the StoryGenres relation, which contains a list of story ID's and genre ID's to tell the genres of stories
$mysqli->query("DROP TABLE IF EXISTS StoryGenres");
$mysqli->query("CREATE TABLE StoryGenres(sID VARCHAR(" . Config::WS_MAX_IDENTIFIER_LENGTH . "), " .
                                          "gID INT, " .
                                          "PRIMARY KEY (sID, gID), " .
                                          "FOREIGN KEY (sID) REFERENCES Story(sID), " .
                                          "FOREIGN KEY (gID) REFERENCES Genre(gID))");

// load up all possible genres into the Genre relation
$mysqli->query("INSERT INTO Genre(name) VALUES('Action'), " .
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
$mysqli->query("INSERT INTO Story(sID, title, author, submitTime, content) " .
    "VALUES('sample01', 'Winter Frost', 'Ace Brown', '2000-01-01 01:30:26', 'Winter is cold.\n\n I feel frosty...'), " .
          "('sample02', 'Summer Blaze', 'Carl Dover', '2001-07-20 16:22:00', 'The day is late, and the night is bright.'), " .
          "('sample03', 'Duck Drive', 'Edgar Funk', '2002-04-20 12:38:48', 'Funky fresh!'), " .
          "('sample04', 'The Depths', 'Giovanni Hamilton', '2003-03-03 08:10:55', 'The cook was there in the morning, and gone at night.'), " .
          "('sample05', 'Murky Swamp', 'Igor Juarez', '2004-02-22 14:18:37', 'In the depths of the swamp laid a monster. The monster was frightening.\n\nIn a single blink of the eye, they vanished.'), " .
          "('sample06', 'Heightened Senses', 'Karen Lumberg', '2005-06-15 10:23:04', 'An explosion boomed through the house. I ran without thinking, only to find myself lost.\n\nHowever, I could hear music that brought me back.'), " .
          "('sample07', 'Fluffy Bunny', 'Madison Nima', '2006-03-30 23:59:42', 'The fluffy bunny was quite funny. She liked too hop around and eat grass.'), " .
          "('sample08', 'Blazing Stallion', 'Omar Price', '2007-12-28 15:00:36', 'Weapons at the ready, the cowboys were about to duel. The clock tower rang through the dusty town, and both raced to reach their weapons and claim victory.'), " .
          "('sample09', 'Good Joke', 'Rachel Seagal', '2008-01-01 11:45:49', 'She knew he was cheating on him. It was just a matter of choosing how to handle the situation...'), " .
          "('sample10', 'Pleasentries', 'Tess Uloo', '2009-11-16 20:16:09', 'It is pleasant to do nothing.\n\nIt is pleasant to shop.\n\nDon\'t forget about watching television, either!')");

// associate some genres with the sample stories
$mysqli->query("INSERT INTO StoryGenres(sID, gID)" .
    "VALUES('sample01', 10), " .
          "('sample01', 1), " .
          "('sample02', 5), " .
          "('sample02', 8), " .
          "('sample03', 6), " .
          "('sample03', 14), " .
          "('sample04', 2), " .
          "('sample04', 9), " .
          "('sample05', 12), " .
          "('sample06', 4), " .
          "('sample06', 13), " .
          "('sample07', 3), " .
          "('sample08', 15), " .
          "('sample09', 7), " .
          "('sample10', 11)");

$mysqli->close();

// let user know process has finished successfully
echo("Done! The database should be ready for use now\n");

?>