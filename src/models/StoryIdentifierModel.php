<?php
namespace cs174\hw3\models;

/**
 * Class GenreModel
 * @package cs174\hw3\models
 *
 * Model for obtaining a story
 * identifier based on a story title
 */
class StoryIdentifierModel extends Model {

    private $storyTitle; // story title to obtain ID for

    /**
     * Creates a new StoryIdentifierModel
     * @param $storyTitle String the title of the story to get a story ID for
     */
    public function __construct($storyTitle) {
        $this->storyTitle = $storyTitle;
    }

    /**
     * Returns current story title this StoryIdentifierModel will look up currently
     * @return String story title this StoryIdentifier will look up currently
     */
    public function getStoryTitle() {
        return $this->storyTitle;
    }

    /**
     * Changes the story title the StoryIdentifierModel will use to look up a story ID
     * @param $storyTitle String new story title for this StoryIdentifier to look up
     */
    public function setStoryTitle($storyTitle) {
        $this->storyTitle = $storyTitle;
    }

    /**
     * Queries database to get story ID for current storyTitle of this
     * StoryIdentifierModel
     * @return bool|\mysqli_result false if query to get story ID failed,
     * or result of query to obtain story ID otherwise
     */
    public function getStoryID() {
        if(!isset($this->storyTitle) || !is_string($this->storyTitle)) {
            echo("Error: to use StoryIdentifierModel you must be using a valid storyTitle field");
            return false;
        }
        $mysqli = parent::getDatabaseConnection();
        $statement = $mysqli->stmt_init();
        $statement->prepare("SELECT sID FROM Story WHERE title = ?");
        $statement->bind_param("s", $this->storyTitle); // s = string
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        $mysqli->close();
        return $result;
    }
}
?>