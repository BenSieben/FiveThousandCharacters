<?php
namespace cs174\hw3\models;
require_once("../configs/Config.php");
use Config;

/**
 * Class TopTenModel
 * @package cs174\hw3\src\models
 *
 * Obtains top ten statistics from the Five Thousand
 * Characters database (top rated, top views, top newest)
 * which can also filter results by a specified genre
 * and (partial or complete) title
 */
class TopTenModel extends Model {
    // Fields for specifying filters for picking top ten
    private $titleFilter; // requested text that the title of selected stories should contain
    private $genreFilter; // requested genre ID for selected stories

    function __construct($titleFilter, $genreFilter) {
        $this -> $titleFilter = $titleFilter;
        $this -> $genreFilter = $genreFilter;
    }

    function setTitleFilter($titleFilter) {
        $this -> $titleFilter = $titleFilter;
    }

    function setGenreFilter($genreFilter) {
        $this -> $genreFilter = $genreFilter;
    }

    function getTopTenViewed() {
        $db = parent::getDatabaseConnection();
        $query = "SELECT sID, title, views FROM Story ";
        if($this -> $titleFilter !== null) {
            $query .= " WHERE title LIKE '%" . $this->titleFilter . "%' ";
            if($this -> $genreFilter !== null) {
                $query .= " AND sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = " . $this->$genreFilter . ") ";
            }
        }
        else if($this -> $genreFilter !== null) {

        }
        $query .=" ORDER BY views DESC, title ASC LIMIT 10;";
        //$result = mysqli_query($db, $query);
        mysqli_close($db);
        return $query;
    }
}
?>