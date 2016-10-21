<?php
namespace cs174\hw3\models;

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

    private $titleFilter; // requested text that the title of selected stories should contain
    private $genreFilter; // requested genre ID for selected stories

    /**
     * Constructs a new TopTenModel
     * @param $titleFilter String to use to filter titles by (NULL to not filter by title)
     * @param $genreFilter String of genre ID to filter by (NULL to not filter by genre)
     */
    public function __construct($titleFilter = NULL, $genreFilter = NULL) {
        $this->titleFilter = $titleFilter;
        $this->genreFilter = $genreFilter;
    }

    /**
     * @return null|String the current title filter for this TopTenModel
     */
    public function getTitleFilter() {
        return $this->titleFilter;
    }

    /**
     * @return String|null the current genre filter for this TopTenModel
     */
    public function getGenreFilter() {
        return $this->genreFilter;
    }

    /**
     * Sets a new title filter for the TopTenModel
     * @param $titleFilter String to use to filter titles by (NULL to not filter by title)
     */
    public function setTitleFilter($titleFilter) {
        $this->titleFilter = $titleFilter;
    }

    /**
     * Sets a new genre filter for the TopTenModel
     * @param $genreFilter Int of genre ID to filter by (NULL to not filter by genre)
     */
    public function setGenreFilter($genreFilter) {
        $this->genreFilter = $genreFilter;
    }

    /**
     * Returns a query of getting top ten rated stories (with specified filters)
     * @return bool|\mysqli_result Result of querying top ten rated stories (with specified filters)
     */
    public function getTopTenRated() {
        $db = parent::getDatabaseConnection();
        $query = "SELECT sID, title, (ratingsSum / ratingsCount) AS avgRating FROM Story ";
        if(isset($this->titleFilter) && is_string($this->titleFilter)) { // add WHERE clause to query if user specified a title filter
            $query .= " WHERE title LIKE '%" . $this->titleFilter . "%' ";
            if(isset($this->genreFilter) && is_string($this->genreFilter)) { // add another condition to WHERE clause of query if genre filter is set
                $query .= " AND sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = " . $this->genreFilter . ") ";
            }
        }
        else if(isset($this->genreFilter) && is_string($this->genreFilter)) { // add WHERE clause to query if user specified a genre filter
            $query .= " WHERE sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = " . $this->genreFilter . ") ";
        }
        $query .=" ORDER BY avgRating DESC, title ASC LIMIT 10;";
        $result = mysqli_query($db, $query);
        mysqli_close($db);
        return $result;
    }

    /**
     * Returns a query of getting top ten viewed stories (with specified filters)
     * @return bool|\mysqli_result Result of querying top ten viewed stories (with specified filters)
     */
    public function getTopTenViewed() {
        $db = parent::getDatabaseConnection();
        $query = "SELECT sID, title, views FROM Story ";
        if(isset($this->titleFilter ) && is_string($this->titleFilter)) { // add WHERE clause to query if user specified a title filter
            $query .= " WHERE title LIKE '%" . $this->titleFilter . "%' ";
            if(isset($this->genreFilter) && is_string($this->genreFilter)) { // add another condition to WHERE clause of query if genre filter is set
                $query .= " AND sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = " . $this->genreFilter . ") ";
            }
        }
        else if(isset($this->genreFilter) && is_string($this->genreFilter)) { // add WHERE clause to query if user specified a genre filter
            $query .= " WHERE sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = " . $this->genreFilter . ") ";
        }
        $query .=" ORDER BY views DESC, title ASC LIMIT 10;";
        $result = mysqli_query($db, $query);
        mysqli_close($db);
        return $result;
    }

    /**
     * Returns a query of getting top ten newest stories (with specified filters)
     * @return bool|\mysqli_result Result of querying top ten newest stories (with specified filters)
     */
    public function getTopTenNewest() {
        $db = parent::getDatabaseConnection();
        $query = "SELECT sID, title, submitTime FROM Story ";
        if(isset($this->titleFilter) && is_string($this->titleFilter)) { // add WHERE clause to query if user specified a title filter
            $query .= " WHERE title LIKE '%" . $this->titleFilter . "%' ";
            if(isset($this->genreFilter) && is_string($this->genreFilter)) { // add another condition to WHERE clause of query if genre filter is set
                $query .= " AND sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = " . $this->genreFilter . ") ";
            }
        }
        else if(isset($this->genreFilter) && is_string($this->genreFilter)) { // add WHERE clause to query if user specified a genre filter
            $query .= " WHERE sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = " . $this->genreFilter . ") ";
        }
        $query .=" ORDER BY submitTime DESC, title ASC LIMIT 10;";
        $result = mysqli_query($db, $query);
        mysqli_close($db);
        return $result;
    }
}
?>