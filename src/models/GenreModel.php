<?php
namespace cs174\hw3\models;

/**
 * Class GenreModel
 * @package cs174\hw3\models
 *
 * Model for obtaining list of all
 * genres in the database, as well as looking
 * up genre IDs based on genre names and getting
 * all genre IDs for an existing story ID
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
     * Queries database to get the genre ID of a given title ID
     * @param $genreTitle String the genre name to get the genre ID of
     * @return bool|\mysqli_result false if genreTitle is not the name
     * of any existing genre in the database, or else the mysqli_result
     * from querying the database for the genre ID of genreTitle
     */
    public function getGenreNameID($genreTitle) {
        if(!isset($genreTitle) || !is_string($genreTitle)) {
            return false;
        }
        $db = parent::getDatabaseConnection();
        $query = "SELECT gID FROM Genre WHERE name = '$genreTitle';";
        $result = mysqli_query($db, $query);
        mysqli_close($db);
        return $result;
    }

    /**
     * Queries database to get all genre ID(s) of a given story ID
     * @param $sID String the story ID to look up genre IDs for
     * @return bool|\mysqli_result false if the story ID is not
     * in the StoryGenres relation, or else the genre ID(s)
     * that are associated with the given story ID
     */
    public function getGenresForStoryID($sID) {
        if(!isset($sID) || !is_string($sID)) {
            return false;
        }
        $db = parent::getDatabaseConnection();
        $query = "SELECT gID FROM StoryGenres WHERE sID='$sID';";
        $result = mysqli_query($db, $query);
        mysqli_close($db);
        return $result;
    }

    /**
     * Queries database to get all genre name(s) of a given
     * array of genre IDs to get the names of
     * @param $genreIDs Array<String> list of genre name(s) to
     * get genre IDs for
     * @return bool|\mysqli_result false if query failed or bad input given, or else
     * gives back query result of getting genre names for all the
     * genre IDs passed to the function
     */
    public function getGenreNames($genreIDs) {
        if(!isset($genreIDs) || !is_array($genreIDs) || count($genreIDs) === 0) {
            return false;
        }
        $db = parent::getDatabaseConnection();
        $gID = $genreIDs[0];
        $query = "SELECT name FROM Genre WHERE gID='$gID' ";
        for($i = 1; $i < count($genreIDs); $i++) {
            $gID = $genreIDs[$i];
            $query .= "OR gID='$gID' ";
        }
        $query .= ";";
        $result = mysqli_query($db, $query);
        mysqli_close($db);
        return $result;
    }

    /**
     * Queries database to get the genre IDs of given title names
     * @param $genreTitles Array<String> the genre title to get the genre ID of
     * @return bool|\mysqli_result false if genreTitle is not the name
     * of any existing genre in the database, or else the mysqli_result
     * from querying the database for the genre IDs of genreTitles
     */
    public function getGenreIDs($genreTitles) {
        if(!isset($genreTitles) || !is_array($genreTitles)) {
            echo("<!-- fail input check -->\n");
            return false;
        }
        $db = parent::getDatabaseConnection();
        $genreTitle = $genreTitles[0];
        $query = "SELECT gID FROM Genre WHERE name = '$genreTitle' ";
        for($i = 1; $i < count($genreTitles); $i++) {
            $genreTitle = $genreTitles[$i];
            $query .= "OR name='$genreTitle' ";
        }
        $query .= ";";
        $result = mysqli_query($db, $query);
        mysqli_close($db);
        return $result;
    }
}
?>