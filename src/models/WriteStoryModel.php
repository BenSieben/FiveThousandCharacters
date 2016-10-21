<?php
namespace cs174\hw3\models;

/**
 * Class WriteSomethingModel
 * @package cs174\hw3\models
 *
 * Model in charge of handling putting new stories
 * into the database as well as updating stories
 * in the database for the Five Thousand Characters
 * website
 */
class WriteSomethingModel extends Model {

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
    public function __construct($sID, $title = NULL, $author = NULL, $content = NULL, $genres = []) {
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
        $db = parent::getDatabaseConnection();
        $result = mysqli_query($db, "SELECT * FROM Story WHERE sID = '" . $this->sID ."';");
        mysqli_close($db);
        if(mysqli_num_rows($result) === 0) {
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
        $db = parent::getDatabaseConnection();
        // first insert the story into the Story relation
        $query = "INSERT INTO Story(sID, title, author, content)" .
            "VALUES('" . $this->sID . "', '" . $this->title . "', '" .
            $this->author . "', '" . $this->content . "');";
        $result = mysqli_query($db, $query);
        mysqli_close($db);
        if(!$result) {
            echo("Error occurred while inserting new story into database.");
            mysqli_close($db);
            return false;
        }
        print_r($this->genres);
        // now add the genre(s) to the StoryGenres relation
        if(isset($this->genres) && is_array($this->genres) && count($this->genres) > 0) {
            echo(" huehuehue");
            $query = "INSERT INTO StoryGenres(sID, gID) VALUES ('" . $this->sID . "', " . $this->genres[0] . ")";
            for($i = 1; $i < count($this->genres); $i++) {
                $query .= ", ('" . $this->sID . "', " . $this->genres[$i] . ")";
            }
            $query .= ";";
            $result = mysqli_query($db, $query);
            if(!$result) {
                echo("Error occurred while inserting new story id / genre id pairs into database.");
                mysqli_close($db);
                return false;
            }
        }
        mysqli_close($db);
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
        $db = parent::getDatabaseConnection();
        // first update the existing story in the database with the same story identifier
        $query = "UPDATE Story SET title='" . $this->title . "', " .
            "author ='" . $this->author . "', " .
            "content ='" . $this->content . "' " .
            "WHERE sID = '" . $this->sID ."';";
        $result = mysqli_query($db, $query);
        if(!$result) {
            echo("Error occurred while updating story database with new data.");
            mysqli_close($db);
            return false;
        }
        // next, clear any current genres for the story (in case they were removed by user)
        $query = "DELETE FROM StoryGenres WHERE sID ='" . $this->sID ."';";
        $result = mysqli_query($db, $query);
        if(!$result) {
            echo("Error occurred while clearing existing story-genre relations from the database.");
            mysqli_close($db);
            return false;
        }
        // now add the new genre(s) to the StoryGenres relation
        if(isset($this->genres) && is_array($this->genres) && count($this->genres) > 0) {
            $query = "INSERT INTO StoryGenres(sID, gID) VALUES ('" . $this->sID . "', " . $this->genres[0] . ")";
            for($i = 1; $i < count($this->genres); $i++) {
                $query .= ", ('" . $this->sID . "', " . $this->genres[$i] . ")";
            }
            $query .= ";";
            $result = mysqli_query($db, $query);
            if(!$result) {
                echo("Error occurred while inserting new story id / genre id pairs into database.");
                mysqli_close($db);
                return false;
            }
        }
        mysqli_close($db);
        return true;
    }
}
?>