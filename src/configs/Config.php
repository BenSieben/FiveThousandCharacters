<?php

/**
 * Class Config
 *
 * Contains a list of constants used in the
 * rest of the website. These should be changed
 * depending on one's own MySQL / file pathing
 * set up to make sure the website functions
 * properly
 */
class Config {

    //Constant for the full URL to where the index.php site is for Five Thousand Characters
    const URL_TO_INDEX = "http://192.168.2.131/hw3/index.php";

    // Database connection constants
    const DB_HOST = "127.0.0.1"; // host for the database
    const DB_USERNAME = "root"; // username for user connecting to database
    const DB_PASSWORD = ""; // password for user connecting to database
    const DB_DATABASE = "FiveThousandCharacters"; // name of database schema to use for all the website data
    const DB_PORT = "3307"; // port that database is on (note how this is NOT default port 3306!)

    // Write something constants
    const WS_MAX_TITLE_LENGTH = 35; // maximum character length of title names for written stories
    const WS_MAX_AUTHOR_LENGTH = 30; // maximum character length of author names for written stories
    const WS_MAX_IDENTIFIER_LENGTH = 20; // maximum character length of identifiers for written stories
    const WS_MAX_STORY_LENGTH = 5000; // maximum character length for written stories

    // Other database constants
    const MAX_GENRE_NAME_LENGTH = 20; // maximum character length of genre names in the database
}
?>