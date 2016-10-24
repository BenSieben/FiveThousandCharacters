<?php
namespace cs174\hw3\controllers;
use cs174\hw3\configs\Config as Config;
use cs174\hw3\models\GenreModel;
use cs174\hw3\models\ReadStoryModel;
use cs174\hw3\models\WriteStoryModel;

/**
 * Class LandingController
 * @package cs174\hw3\controllers
 *
 * Additional Controller for write View
 * which saves story to database (given
 * valid data) and then redirects back
 * to WriteController to perform
 * POST REDIRECT GET pattern
 */
class WriteSubmitController extends Controller {

    private $goodUserInput = true; // flag to keep track if there was something bad with the user's input or not

    /**
     * Looks at current values in PHP super globals
     * such as $_REQUEST and $_SESSION to potentially save a story
     * to the database and then redirect user back to WriteController
     */
    public function processForms() {
        $arguments = $this->filterAndSanitizeArguments(); // arguments = added to URL to specify values to fill in on write something view

        if($this->goodUserInput) { // only add story to database if they gave good input on the write something form
            $this->addStoryToDatabase(); // try to add the story to the database
        }

        // check special case where only identifier was filled, in which case
        //   we load up the story with that identifier from the database (if it exists)
        $loadArguments = $this->checkForLoad();
        if($loadArguments !== false) { // if not false, we will be loading data into Write view
            // redirect user back to WriteController with loaded arguments set if loading was successful
            header("Location:" . Config::URL_TO_INDEX . "?c=WriteController&m=processForms" . $loadArguments);
            exit();
        }

        header("Location:" . Config::URL_TO_INDEX . "?c=WriteController&m=processForms" . $arguments); // redirect user back to WriteController with arguments set
    }

    private function filterAndSanitizeArguments() {
        $arguments = "";
        $errorMessages = []; // this will keep track of error messages to relay to user regarding their form input
        // add all write view form variables to arguments string to reload the data when
        //   WriteController needs to set up Write view after redirect if the
        //   $_REQUEST is set (after filtering and sanitizing the form data)
        if(isset($_REQUEST['writeIdentifier'])) {
            // if there are characters in phrase filter besides those in the replace below, take them out
            if(strcmp($_REQUEST['writeIdentifier'], preg_replace('/[^a-zA-Z0-9.\'\"~`!@#$^&\*()-=\+\|\]}\[{;:,?<> ]/', '', $_REQUEST['writeIdentifier'])) !== 0) {
                $_REQUEST['writeIdentifier'] = preg_replace('/[^a-zA-Z0-9.\'\"~`!@#$^&\*()-=\+\|\]}\[{;:,?<> ]/', '', $_REQUEST['writeIdentifier']);
                array_push($errorMessages, 'Error: illegal special characters detected in identifier. They have been removed');
                $this->goodUserInput = false; // mark input as bad if input had rejected special characters
            }
            if(strlen( $_REQUEST['writeIdentifier']) > Config::WS_MAX_IDENTIFIER_LENGTH) {
                array_push($errorMessages, 'Error: identifier is too long. Max length is ' . Config::WS_MAX_IDENTIFIER_LENGTH);
                $arguments .= "&wi=" . urlencode(substr( $_REQUEST['writeIdentifier'], 0, Config::WS_MAX_IDENTIFIER_LENGTH)); // wi = write identifier (truncated)
                $this->goodUserInput = false; // mark input as bad if input was too long somewhere
            } else {
                $arguments .= "&wi=" .  urlencode($_REQUEST['writeIdentifier']); // wi = write identifier
            }
        }
        if(isset($_REQUEST['writeTitle'])) {
            // if there are characters in phrase filter besides those in the replace below, take them out
            if(strcmp($_REQUEST['writeTitle'], preg_replace('/[^a-zA-Z0-9.\'\"~`!@#$^&\*()-=\+\|\]}\[{;:,?<> ]/', '', $_REQUEST['writeTitle'])) !== 0) {
                $_REQUEST['writeTitle'] = preg_replace('/[^a-zA-Z0-9.\'\"~`!@#$^&\*()-=\+\|\]}\[{;:,?<> ]/', '', $_REQUEST['writeTitle']);
                array_push($errorMessages, 'Error: illegal special characters detected in title. They have been removed');
                $this->goodUserInput = false; // mark input as bad if input had rejected special characters
            }
            if(strlen($_REQUEST['writeTitle']) > Config::WS_MAX_TITLE_LENGTH) {
                array_push($errorMessages, 'Error: title is too long. Max length is ' . Config::WS_MAX_TITLE_LENGTH);
                $arguments .= "&wt=" . urlencode(substr($_REQUEST['writeTitle'], 0, Config::WS_MAX_TITLE_LENGTH)); // wt = write title (truncated)
                $this->goodUserInput = false; // mark input as bad if input was too long somewhere
            } else {
                $arguments .= "&wt=" . urlencode($_REQUEST['writeTitle']); // wt = write title
            }
        }
        if(isset($_REQUEST['writeAuthor'])) {
            // if there are characters in phrase filter besides those in the replace below, take them out
            if(strcmp($_REQUEST['writeAuthor'], preg_replace('/[^a-zA-Z0-9.\'\"~`!@#$^&\*()-=\+\|\]}\[{;:,?<> ]/', '', $_REQUEST['writeAuthor'])) !== 0) {
                $_REQUEST['writeAuthor'] = preg_replace('/[^a-zA-Z0-9.\'\"~`!@#$^&\*()-=\+\|\]}\[{;:,?<> ]/', '', $_REQUEST['writeAuthor']);
                array_push($errorMessages, 'Error: illegal special characters detected in author. They have been removed');
                $this->goodUserInput = false; // mark input as bad if input had rejected special characters
            }
            if(strlen($_REQUEST['writeAuthor']) > Config::WS_MAX_AUTHOR_LENGTH) {
                array_push($errorMessages, 'Error: author is too long. Max length is ' . Config::WS_MAX_AUTHOR_LENGTH);
                $arguments .= "&wa=" . urlencode(substr($_REQUEST['writeAuthor'], 0, Config::WS_MAX_AUTHOR_LENGTH)); // wa = write author (truncated)
                $this->goodUserInput = false; // mark input as bad if input was too long somewhere
            } else {
                $arguments .= "&wa=" . urlencode($_REQUEST['writeAuthor']); // wa = write author
            }
        }
        if(isset($_REQUEST['writeGenres'])) {
            $writeGenresURLVersion = http_build_query(array('wg' => $_REQUEST['writeGenres'])); // this lets us pass genres array via GET easily
            $arguments .= "&" . $writeGenresURLVersion; // wg = write genres
        }
        if(isset($_REQUEST['writeStory'])) {
            // replace return carriage with nothing
            $_REQUEST['writeStory'] = str_replace("\r", "", $_REQUEST['writeStory']);
            if(strlen($_REQUEST['writeStory']) > Config::WS_MAX_STORY_LENGTH) {
                array_push($errorMessages, 'Error: story is too long. Max length is ' . Config::WS_MAX_STORY_LENGTH);
                $arguments .= "&ws=" . urlencode(substr($_REQUEST['writeStory'], 0, Config::WS_MAX_STORY_LENGTH)); // ws = write story (truncated)
                $this->goodUserInput = false; // mark input as bad if input was too long somewhere
            } else {
                $arguments .= "&ws=" . urlencode($_REQUEST['writeStory']); // ws = write story
            }
        }
        if(count($errorMessages) > 0) { // add error messages array to arguments if any errors occurred
            $arguments .= "&" . http_build_query(array('err' => $errorMessages));
        }
        return $arguments;
    }

    /**
     * Checks if the write view should load data from the database or not,
     * and if so will return $loadArguments with proper values from the database
     * @return bool|String gives back proper $dataArguments if all
     * queries were successful and the load condition is met, or else
     * false
     */
    private function checkForLoad() {
        if(isset($_REQUEST['writeIdentifier']) && strcmp($_REQUEST['writeIdentifier'], '') !== 0
            && (!isset($_REQUEST['writeTitle']) || strcmp($_REQUEST['writeTitle'], '') === 0)
            && (!isset($_REQUEST['writeAuthor']) || strcmp($_REQUEST['writeAuthor'], '') === 0)
            && (!isset($_REQUEST['writeGenres']) || count($_REQUEST['writeGenres']) === 0)
            && (!isset($_REQUEST['writeStory']) || strcmp($_REQUEST['writeStory'], '') === 0)) {
            $readStoryModel = new ReadStoryModel($_REQUEST['writeIdentifier']);
            $result = $readStoryModel->editStory();
            if($result !== false) { // only load the story if the writeIdentifier is an actual story ID
                $loadArguments = "";
                foreach($result as $row) {
                    // there should only be a single row in the query result; this foreach is to more easily extract the data of the story tuple
                    $loadArguments .= "&wi=" . urlencode($row['sID']); // wi = write identifier
                    $loadArguments .= "&wt=" . urlencode($row['title']); // wt = write title
                    $loadArguments .= "&wa=" . urlencode($row['author']); // wa = write author
                    // now we have to load up $data['writeGenres'] with whatever genres the story is associated with
                    // first get the genre IDs based on the story ID
                    $genreModel = new GenreModel();
                    $resultGenres = $genreModel->getGenresForStoryID($row['sID']);
                    if($resultGenres !== false) {
                        $storyGenreIDs = [];
                        foreach($resultGenres as $genreIDRow) {
                            array_push($storyGenreIDs, $genreIDRow['gID']);
                        }
                        // then get the genre names based on genre IDs to select them in the Read view
                        $resultGenreNames = $genreModel->getGenreNames($storyGenreIDs);
                        if($resultGenreNames !== false) {
                            $writeGenres = [];
                            foreach($resultGenreNames as $genreName) {
                                array_push($writeGenres, $genreName['name']);
                            }
                            $writeGenresURLVersion = http_build_query(array('wg' => $writeGenres)); // this lets us pass genres array via GET easily
                            $loadArguments .= "&" . $writeGenresURLVersion; // wg = write genres
                            $loadArguments .= "&ws=" . urlencode(str_replace("\n", "%0A", $row['content'])); // ws = write story (replace newlines with %0A)
                            return $loadArguments;
                        }
                    }
                }
            }
        }
        return false; // return false if any of the conditions failed (bad queries / improper $_REQUEST set up)
    }

    /**
     * Attempts to add the current write something content
     * to the database, if all the information is filled out
     * @return bool true if adding story was successful, false otherwise
     */
    private function addStoryToDatabase() {
        // if all input fields have been filled out with something, then we will add the story to database
        if(isset($_REQUEST['writeIdentifier']) && strcmp($_REQUEST['writeIdentifier'], '') !== 0
            && (!isset($_REQUEST['writeTitle']) || strcmp($_REQUEST['writeTitle'], '') !== 0)
            && (!isset($_REQUEST['writeAuthor']) || strcmp($_REQUEST['writeAuthor'], '') !== 0)
            && (!isset($_REQUEST['writeGenres']) || count($_REQUEST['writeGenres']) > 0)
            && (!isset($_REQUEST['writeStory']) || strcmp($_REQUEST['writeStory'], '') !== 0)) {
            // first, the $_REQUEST['writeGenres'] must be converted back to genre IDs instead of genre names
            //   so that we can add to the StoryGenres relation
            $genreModel = new GenreModel();
            $result = $genreModel->getGenreIDs($_REQUEST['writeGenres']);
            if($result === false) {
                return false;
            }
            $genreIDs = []; // this array will hold all the genre IDs of the genre titles specified in $_REQUEST['writeGenres']
            foreach($result as $row) {
                array_push($genreIDs, $row['gID']); // push genre IDs in $result to $genreIDs array
            }
            // remove any carriage returns that might be in story content before saving and convert back to newlines instead of %0A
            $_REQUEST['writeStory'] = str_replace("\r", "", $_REQUEST['writeStory']);
            //$writeStory = str_replace("%0A", "\n", $writeStory);
            $writeStoryModel = new WriteStoryModel(urldecode($_REQUEST['writeIdentifier']), urldecode($_REQUEST['writeTitle']),
                urldecode($_REQUEST['writeAuthor']), urldecode($_REQUEST['writeStory']), $genreIDs);
            $writeStoryModel->addStory();
            return true;
        }
        else {
            // to avoid breaking the header, these lines have been commented out
            //   if data is not persisted in database even when information is filled out,
            //   then uncomment these lines to debug
            //echo("Data is not filled out to be submitted to database.");
            //print_r($_REQUEST);
            return false;
        }
    }
}
?>