<?php
namespace cs174\hw3\models;
use Config;

/**
 * Class Model
 * @package cs174\hw3\src\models
 *
 * Superclass of any class used as the
 * Model for the website
 */
class Model {
    /**
     * Creates and returns a database connection for the Five Thousand Characters website
     * @return \mysqli the database connection (based on settings in Config.php)
     */
    function getDatabaseConnection() {
        return mysqli_connect(Config::DB_HOST, Config::DB_USERNAME, Config::DB_PASSWORD, Config::DB_DATABASE, Config::DB_PORT);
    }
}

?>