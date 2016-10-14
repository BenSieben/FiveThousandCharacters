<?php
require_once "Config.php";
/**
 * PHP script which utilizes the Config class
 * in order to connect to a DBMS and create a
 * new database which will be used to store
 * all information for the website (also creates
 * some starter data to initialize the database)
 */
// first attempt to establish a connection to the database
$db = mysqli_connect(Config::DB_HOST, Config::DB_USERNAME, Config::DB_PASSWORD, "" , Config::DB_PORT);
if(!$db) {
    echo("Error connecting to \"" . Config::DB_HOST . "\" with username \"" . Config::DB_USERNAME
        . "\", password \"" . Config::DB_PASSWORD . "\", and port \"" . Config::DB_PORT . "\".");
    return;
}
echo("Connection success, now setting up database " . Config::DB_DATABASE . " (any existing " .
    "database with this name will be cleared)\n");
mysqli_select_db($db, Config::DB_DATABASE);
$result = mysqli_query($db, "SHOW TABLES");
if(!$result) {
    echo("hi");
    return;
}
for($i = 0; $i < mysqli_num_rows($result); $i++) {
    print_r(mysqli_fetch_row($result));
}
//$result = mysqli_query($db, "INSERT INTO Names VALUES(3, 30), (4, 40)");
//$result = mysqli_query($db, "SELECT * FROM Names");

?>