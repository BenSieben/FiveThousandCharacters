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
        $db = parent::getDatabaseConnection();
        $query = "SELECT sID, title, author, submitTime, (ratingsSum / ratingsCount) " .
            "AS avgRating, content FROM Story WHERE sID ='" . $this->sID ."';";
        $result = mysqli_query($db, $query);
        if(!$result) {
            echo("Failed to retrieve story data from the database.");
            return false;
        }
        return $result;
    }

    /**
     * Retrieves the query result from selecting a story from the database based on the story ID of this ReadStoryModel
     * for reading (view count will go up)
     * @return bool|\mysqli_result query result for retrieving the story from the database
     */
    public function readStory() {
        // first retrieve the story contents just like editing requires
        $result1 = $this->editStory();
        if(!$result1) {
            return false;
        }
        // if reading was successful, also increment the views for this story by 1
        $db = parent::getDatabaseConnection();
        $query = "UPDATE Story SET views = views + 1 WHERE sID = '" . $this->sID ."';";
        $result2 = mysqli_query($db, $query);
        if(!$result2) {
            echo("Error updating views for the specified story.");
            return false;
        }
        return $result1;
    }
}
?>