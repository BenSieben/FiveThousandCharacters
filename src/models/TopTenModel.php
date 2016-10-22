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
        $this->titleFilter = "%" . $titleFilter . "%"; // add percent signs because this is used in LIKE in MySQL queries
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
        $this->titleFilter = "%" . $titleFilter . "%"; // add percent signs because this is used in LIKE in MySQL queries
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
        if(isset($this->titleFilter) && is_string($this->titleFilter)) { // add WHERE clause to query if user specified a title filter
            if(isset($this->genreFilter) && is_int($this->genreFilter)) { // need to check title filter and genre filter
                $mysqli = parent::getDatabaseConnection();
                $statement = $mysqli->stmt_init();
                $statement->prepare("SELECT sID, title, (ratingsSum / ratingsCount) AS avgRating FROM Story " .
                    "WHERE title LIKE ? AND sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = ?)" .
                    "ORDER BY avgRating DESC, title ASC LIMIT 10");
                $statement->bind_param("si", $this->titleFilter, $this->genreFilter); // s = string, i = integer
                $statement->execute();
                $result = $statement->get_result();
                $statement->close();
                $mysqli->close();
                return $result;
            }
            else { // only need to check title filter
                $mysqli = parent::getDatabaseConnection();
                $statement = $mysqli->stmt_init();
                $statement->prepare("SELECT sID, title, (ratingsSum / ratingsCount) AS avgRating FROM Story " .
                    "WHERE title LIKE ? " .
                    "ORDER BY avgRating DESC, title ASC LIMIT 10");
                $statement->bind_param("s", $this->titleFilter); // s = string
                $statement->execute();
                $result = $statement->get_result();
                $statement->close();
                $mysqli->close();
                return $result;
            }
        }
        else if(isset($this->genreFilter) && is_int($this->genreFilter)) { // only need to check genre filter
            $mysqli = parent::getDatabaseConnection();
            $statement = $mysqli->stmt_init();
            $statement->prepare("SELECT sID, title, (ratingsSum / ratingsCount) AS avgRating FROM Story " .
                "WHERE sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = ?)" .
                "ORDER BY avgRating DESC, title ASC LIMIT 10");
            $statement->bind_param("i", $this->genreFilter); // i = integer
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            $mysqli->close();
            return $result;
        }
        else { // run select without any WHERE clause if there is no title filter or genre filter
            $mysqli = parent::getDatabaseConnection();
            $result = $mysqli->query("SELECT sID, title, (ratingsSum / ratingsCount) AS avgRating FROM Story " .
                "ORDER BY avgRating DESC, title ASC LIMIT 10");
            $mysqli->close();
            return $result;
        }
    }

    /**
     * Returns a query of getting top ten viewed stories (with specified filters)
     * @return bool|\mysqli_result Result of querying top ten viewed stories (with specified filters)
     */
    public function getTopTenViewed() {
        if(isset($this->titleFilter) && is_string($this->titleFilter)) { // add WHERE clause to query if user specified a title filter
            if(isset($this->genreFilter) && is_int($this->genreFilter)) { // need to check title filter and genre filter
                $mysqli = parent::getDatabaseConnection();
                $statement = $mysqli->stmt_init();
                $statement->prepare("SELECT sID, title, views FROM Story " .
                    "WHERE title LIKE ? AND sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = ?)" .
                    "ORDER BY views DESC, title ASC LIMIT 10");
                $statement->bind_param("si", $this->titleFilter, $this->genreFilter); // s = string, i = integer
                $statement->execute();
                $result = $statement->get_result();
                $statement->close();
                $mysqli->close();
                return $result;
            }
            else { // only need to check title filter
                $mysqli = parent::getDatabaseConnection();
                $statement = $mysqli->stmt_init();
                $statement->prepare("SELECT sID, title, views FROM Story " .
                    "WHERE title LIKE ? " .
                    "ORDER BY views DESC, title ASC LIMIT 10");
                $statement->bind_param("s", $this->titleFilter); // s = string
                $statement->execute();
                $result = $statement->get_result();
                $statement->close();
                $mysqli->close();
                return $result;
            }
        }
        else if(isset($this->genreFilter) && is_int($this->genreFilter)) { // only need to check genre filter
            $mysqli = parent::getDatabaseConnection();
            $statement = $mysqli->stmt_init();
            $statement->prepare("SELECT sID, title, views FROM Story " .
                "WHERE  sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = ?)" .
                "ORDER BY views DESC, title ASC LIMIT 10");
            $statement->bind_param("i", $this->genreFilter); // i = integer
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            $mysqli->close();
            return $result;
        }
        else { // run select without any WHERE clause if there is no title filter or genre filter
            $mysqli = parent::getDatabaseConnection();
            $result = $mysqli->query("SELECT sID, title, views FROM Story " .
                "ORDER BY views DESC, title ASC LIMIT 10");
            $mysqli->close();
            return $result;
        }
    }

    /**
     * Returns a query of getting top ten newest stories (with specified filters)
     * @return bool|\mysqli_result Result of querying top ten newest stories (with specified filters)
     */
    public function getTopTenNewest() {
        if(isset($this->titleFilter) && is_string($this->titleFilter)) { // add WHERE clause to query if user specified a title filter
            if(isset($this->genreFilter) && is_int($this->genreFilter)) { // need to check title filter and genre filter
                $mysqli = parent::getDatabaseConnection();
                $statement = $mysqli->stmt_init();
                $statement->prepare("SELECT sID, title, submitTime FROM Story " .
                    "WHERE title LIKE ? AND sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = ?)" .
                    "ORDER BY submitTime DESC, title ASC LIMIT 10");
                $statement->bind_param("si", $this->titleFilter, $this->genreFilter); // s = string, i = integer
                $statement->execute();
                $result = $statement->get_result();
                $statement->close();
                $mysqli->close();
                return $result;
            }
            else { // only need to check title filter
                $mysqli = parent::getDatabaseConnection();
                $statement = $mysqli->stmt_init();
                $statement->prepare("SELECT sID, title, submitTime FROM Story " .
                    "WHERE title LIKE ? " .
                    "ORDER BY submitTime DESC, title ASC LIMIT 10");
                $statement->bind_param("s", $this->titleFilter); // s = string
                $statement->execute();
                $result = $statement->get_result();
                $statement->close();
                $mysqli->close();
                return $result;
            }
        }
        else if(isset($this->genreFilter) && is_int($this->genreFilter)) { // only need to check genre filter
            $mysqli = parent::getDatabaseConnection();
            $statement = $mysqli->stmt_init();
            $statement->prepare("SELECT sID, title, submitTime FROM Story " .
                "WHERE  sID IN (SELECT SG.sID FROM StoryGenres AS SG WHERE SG.gID = ?)" .
                "ORDER BY submitTime DESC, title ASC LIMIT 10");
            $statement->bind_param("i", $this->genreFilter); // i = integer
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            $mysqli->close();
            return $result;
        }
        else { // run select without any WHERE clause if there is no title filter or genre filter
            $mysqli = parent::getDatabaseConnection();
            $result = $mysqli->query("SELECT sID, title, submitTime FROM Story " .
                "ORDER BY submitTime DESC, title ASC LIMIT 10");
            $mysqli->close();
            return $result;
        }
    }
}
?>