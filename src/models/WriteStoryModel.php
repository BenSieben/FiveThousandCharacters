<?php
namespace cs174\hw3\models;

/**
 * Class WriteStoryModel
 * @package cs174\hw3\models
 *
 * Model in charge of handling putting new stories
 * into the database as well as updating stories
 * in the database for the Five Thousand Characters
 * website
 */
class WriteStoryModel extends Model {

    private $sID; // story identifier
    private $title; // story title
    private $author; // story author
    private $content; // story content
    private $genres; // story genre(s)

    /**
     * Creates a new WriteSomethingModel for creating or updating stories in the database
     * @param $sID String the identifier of the story
     * @param $title String the title of the story
     * @param $author String the author of the story
     * @param $content String the actual contents of the story
     * @param $genres Array<Int> the list of genre ID(s) that the story is categorized for
     */
    public function __construct($sID, $title, $author, $content, $genres) {
        $this->sID = $sID;
        $this->title = $title;
        $this->author = $author;
        $this->content = $content;
        $this->genres = $genres;
    }

    /**
     * @return String the story identifier of the current story being added
     */
    public function getsID() {
        return $this->sID;
    }

    /**
     * @return null|String the title of the current story being added
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return null|String the author of the current story being added
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * @return null|String the content of the current story being added
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @return Array<Int> the list of genre ID(s) that the story is categorized for
     */
    public function getGenres() {
        return $this->genres;
    }

    /**
     * @param $sID String the new story identifier to use for the current story being added
     */
    public function setsID($sID) {
        $this->sID = $sID;
    }

    /**
     * @param $title String the new title to use for the current story being added
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @param $author String the new author to use for the current story being added
     */
    public function setAuthor($author) {
        $this->author = $author;
    }

    /**
     * @param $content String the new content to use for the current story being added
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * @param $genres Array<Int> the new genre ID(s) to use for categorizing the current story being added
     */
    public function setGenres($genres) {
        $this->genres = $genres;
    }

    /**
     * @return bool|\mysqli_result returns true if the add was successful, and false if the add failed for any reason
     */
    public function addStory() {
        // first determine if we should update an existing story
        //   or add a brand-new story by checking the story identifier
        $mysqli = parent::getDatabaseConnection();
        $statement = $mysqli->stmt_init();
        $statement->prepare("SELECT * FROM Story WHERE sID =  ?");
        $statement->bind_param("s", $this->sID); // s = string
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        $mysqli->close();
        if(mysqli_num_rows($result) === 0) { // if result relation is empty, that means sID is not inside database yet
            // if sID is new, then we are adding a brand new story
            return $this->createStory();
        }
        else {
            // if sID is old, then we are updating an old story
            return $this->updateStory();
        }
    }

    /**
     * Attempts to add a new story's values into the database
     * @return bool true if story creation worked, and false if not
     */
    private function createStory() {
        // make sure all fields are set up properly
        if(!isset($this->sID) || !isset($this->title) || !isset($this->author) || !isset($this->content)) {
            return false;
        }
        if(!is_string($this->sID) || !is_string($this->title) || !is_string($this->author) || !is_string($this->content)) {
            return false;
        }
        $mysqli = parent::getDatabaseConnection();
        // first insert the story into the Story relation
        $statement = $mysqli->stmt_init();
        $statement->prepare("INSERT INTO Story(sID, title, author, content) VALUES(?, ?, ?, ?)");
        $statement->bind_param("ssss", $this->sID, $this->title, $this->author, $this->content); // s = string
        $statement->execute();
        $statement->close();
        if($mysqli->errno !== 0) { // if mysqli error code is not 0, there was error
            echo("<!-- Error occurred while inserting new story into database. -->\n");
            $mysqli->close();
            return false;
        }

        // now add the genre(s) to the StoryGenres relation
        if(isset($this->genres) && is_array($this->genres) && count($this->genres) > 0) {
            $query = "INSERT INTO StoryGenres(sID, gID) VALUES ('" . $this->sID . "', " . $this->genres[0] . ")";
            for($i = 1; $i < count($this->genres); $i++) {
                $query .= ", ('" . $this->sID . "', " . $this->genres[$i] . ")";
            }
            $result = $mysqli->query($query);
            if(!$result) {
                echo("<!-- Error occurred while inserting new story id / genre id pairs into database. -->\n");
                $mysqli->close();
                return false;
            }
        }
        $mysqli->close();
        return true;
    }

    /**
     * Attempts to update an existing story's values in the database
     * @return bool true if story update worked, and false if not
     */
    private function updateStory() {
        // make sure all fields are set up properly
        if(!isset($this->sID) || !isset($this->title) || !isset($this->author) || !isset($this->content)) {
            return false;
        }
        if(!is_string($this->sID) || !is_string($this->title) || !is_string($this->author) || !is_string($this->content)) {
            return false;
        }
        $mysqli = parent::getDatabaseConnection();
        // first insert the story into the Story relation
        $statement = $mysqli->stmt_init();
        $statement->prepare("UPDATE Story SET title = ?, author = ?, content = ? WHERE sID = ?");
        $statement->bind_param("ssss", $this->title, $this->author, $this->content, $this->sID); // s = string
        $statement->execute();
        $statement->close();
        if($mysqli->errno !== 0) { // if mysqli error code is not 0, there was error
            echo("<!-- Error occurred while updating story database with new data. -->\n");
            $mysqli->close();
            return false;
        }

        // next, clear any current genres for the story (in case they were removed by user)
        $statement = $mysqli->stmt_init();
        $statement->prepare("DELETE FROM StoryGenres WHERE sID = ?");
        $statement->bind_param("s", $this->sID); // s = string
        $statement->execute();
        $statement->close();
        if($mysqli->errno !== 0) { // if mysqli error code is not 0, there was error
            echo("<!-- Error occurred while clearing existing story-genre relations from the database. -->");
            $mysqli->close();
            return false;
        }

        // now add the genre(s) to the StoryGenres relation
        if(isset($this->genres) && is_array($this->genres) && count($this->genres) > 0) {
            $query = "INSERT INTO StoryGenres(sID, gID) VALUES ('" . $this->sID . "', " . $this->genres[0] . ")";
            for($i = 1; $i < count($this->genres); $i++) {
                $query .= ", ('" . $this->sID . "', " . $this->genres[$i] . ")";
            }
            $result = $mysqli->query($query);
            if(!$result) {
                echo("<!-- Error occurred while inserting new story id / genre id pairs into database. -->");
                $mysqli->close();
                return false;
            }
        }
        $mysqli->close();
        return true;
    }
}
?>