<?php
namespace cs174\hw3\models;

/**
 * Class RatingAdderModel
 * @package cs174\hw3\models
 *
 * Updates database ratings for when
 * users submit their reviews for stories
 */
class RatingAdderModel extends Model {

    private $storyID; // story identifier of story user is rating
    private $rating; // the actual rating the user gave to the story

    /**
     * Creates a new RatingAdderModel, responsible for adding new user ratings for stories to the database
     * @param $storyID String the identifier of the story being rated by the user
     * @param $rating Int the user's rating of the story
     */
    public function __construct($storyID, $rating) {
        $this->storyID = $storyID;
        $this->rating = $rating;
    }

    /**
     * @return String the story ID which is being rated by the user
     */
    public function getStoryID() {
        return $this->storyID;
    }

    /**
     * @return Int the rating the user would like to give to a story
     */
    public function getRating() {
        return $this->rating;
    }

    /**
     * @param $storyID String the new story ID that the user is rating
     */
    public function setStoryID($storyID) {
        $this->storyID = $storyID;
    }

    /**
     * @param $rating Int the new rating the user would like to give to a story
     */
    public function setRating($rating) {
        $this->rating = $rating;
    }

    /**
     * Adds a rating to the database, with values dependent on the storyID and rating of this RatingAdderModel
     * @return bool false if the story identifier / rating are invalid or the query failed, otherwise
     * true
     */
    public function addStoryRating() {
        // check that storyID and rating are set and the correct data type
        if(!isset($this->storyID) || !isset($this->rating)) {
            return false;
        }
        if(!is_string($this->storyID) || !is_int($this->rating)) {
            return false;
        }

        // given valid fields, we can query the database to add the rating
        $mysqli = parent::getDatabaseConnection();
        $statement = $mysqli->stmt_init();
        $statement->prepare("UPDATE Story SET ratingsSum = ratingsSum + ?, ratingsCount = ratingsCount + 1 WHERE sID = ?");
        $statement->bind_param("is", $this->rating, $this->storyID); // i = integer, s = string
        $statement->execute();
        $statement->close();
        if($mysqli->errno !== 0) {
            $mysqli->close();
            return false;
        }
        $mysqli->close();
        return true;
    }
}
?>