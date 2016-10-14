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
    const DB_HOST = "127.0.0.1"; // host for the database
    const DB_USERNAME = "root"; // username for user connecting to database
    const DB_PASSWORD = ""; // password for user connecting to database
    const DB_DATABASE = "FiveThousandCharacters"; // name of database schema to use for all the website data
    const DB_PORT = "3307"; // port that database is on (note how this is NOT default port 3306!)
}
?>