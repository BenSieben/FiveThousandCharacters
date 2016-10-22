<?php
namespace cs174\hw3\models;

/**
 * Class ReadStoryModel
 * @package cs174\hw3\models
 *
 * Model that handles retrieving stories for the user
 * to read (or edit) for the Five Thousand Characters
 * website
 */
class ReadStoryModel extends Model {

    private $sID; // story identifier to use when retrieving a story from the database

    /**
     * Creates a new ReadStoryModel
     * @param $sID String the story identifier of the story to be read
     */
    public function __construct($sID) {
        $this->sID = $sID;
    }

    /**
     * @return String the current story identifier of this ReadStoryModel
     */
    public function getsID() {
        return $this->sID;
    }

    /**
     * @param $sID String the new story identifier to read from with this ReadStoryModel
     */
    public function setsID($sID) {
        $this->sID = $sID;
    }

    /**
     * Retrieves the query result from selecting a story from the database based on the story ID of this ReadStoryModel
     * for editing (view count will not go up)
     * @return bool|\mysqli_result query result for retrieving the story from the database
     */
    public function editStory() {
        if(!isset($this->sID) || !is_string($this->sID)) {
            echo("To use the ReadStoryModel, a String sID must be set. Please set a valid sID.");
            return false;
        }
        $mysqli = parent::getDatabaseConnection();
        $statement = $mysqli->stmt_init();
        $statement->prepare("SELECT sID, title, author, submitTime, (ratingsSum / ratingsCount) AS avgRating, content FROM Story WHERE sID = ?");
        $statement->bind_param("s", $this->sID); // s = string
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        $mysqli->close();
        if(!$result) {
            echo("Failed to retrieve story data from the database.");
            return false;
        }
        return $result;
    }

    /**
     * Retrieves the query result from selecting a story from the database based on the story ID of this ReadStoryModel
     * for reading (view count will go up)
     * @return bool true if query worked, false if not
     */
    public function readStory() {
        // first retrieve the story contents just like editing requires
        $result1 = $this->editStory();
        if(!$result1) {
            return false;
        }
        // if reading was successful, also increment the views for this story by 1
        $mysqli = parent::getDatabaseConnection();
        $statement = $mysqli->stmt_init();
        $statement->prepare("UPDATE Story SET views = views + 1 WHERE sID = ?");
        $statement->bind_param("s", $this->sID); // s = string
        $statement->execute();
        if($mysqli->affected_rows <= 0) { // this will check if we have updated at least one row (which should always happen in successful update)
            echo("Error updating views for the specified story.");
            $statement->close();
            $mysqli->close();
            return false;
        }
        $statement->close();
        $mysqli->close();
        return $result1;
    }
}
?>