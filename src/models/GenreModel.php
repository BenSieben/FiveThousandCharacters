<?php
namespace cs174\hw3\models;

/**
 * Class GenreModel
 * @package cs174\hw3\models
 *
 * Model for obtaining list of all
 * genres in the database, as well as looking
 * up genre IDs based on genre names
 */
class GenreModel extends Model {

    /**
     * Queries database to get all the genre IDs and genre names
     * @return bool|\mysqli_result false if query to get all genre IDs and names failed,
     * or result of query to obtain all genre IDs and names in mysqli_query result otherwise
     */
    public function getListOfGenres() {
        $db = parent::getDatabaseConnection();
        $query = "SELECT * FROM Genre;";
        $result = mysqli_query($db, $query);
        mysqli_close($db);
        return $result;
    }

    /**
     * Queries database to get the genre ID of a given ID title
     * @param $genreTitle String the genre title to get the genre ID of
     * @return bool|\mysqli_result false if genreTitle is not the name
     * of any existing genre in the database, or else the mysqli_result
     * from querying the database for the genre ID of genreTitle
     */
    public function getGenreTitleID($genreTitle) {
        $db = parent::getDatabaseConnection();
        $query = "SELECT gID FROM Genre WHERE title = '$genreTitle'";
        $result = mysqli_query($db, $query);
        mysqli_close($db);
        return $result;
    }
}
?>