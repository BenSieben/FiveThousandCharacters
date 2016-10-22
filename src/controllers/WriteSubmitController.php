<?php
namespace cs174\hw3\controllers;
use Config;
use cs174\hw3\models\GenreModel;
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

    /**
     * Looks at current values in PHP super globals
     * such as $_REQUEST and $_SESSION to potentially save a story
     * to the database and then redirect user back to WriteController
     */
    public function processForms() {
        // add all write view form variables to $_SESSION to reload the data when
        //   WriteController needs to set up Write view after redirect if the
        //   $_REQUEST is set
        if(isset($_REQUEST['writeIdentifier'])) {
            $_SESSION['writeIdentifier'] = $_REQUEST['writeIdentifier'];
        }
        if(isset($_REQUEST['writeTitle'])) {
            $_SESSION['writeTitle'] = $_REQUEST['writeTitle'];
        }
        if(isset($_REQUEST['writeAuthor'])) {
            $_SESSION['writeAuthor'] = $_REQUEST['writeAuthor'];
        }
        if(isset($_REQUEST['writeGenres'])) {
            $_SESSION['writeGenres'] = $_REQUEST['writeGenres'];
        }
        if(isset($_REQUEST['writeStory'])) {
            $_SESSION['writeStory'] = $_REQUEST['writeStory'];
        }

        $this->addStoryToDatabase();
        header("Location:" . Config::URL_TO_INDEX . "?c=WriteController&m=processForms"); // redirect user back to WriteController
    }

    /**
     * Attempts to add the current write something content
     * to the database, if all the information is filled out
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
            // remove any carriage returns that might be in story content before saving
            $_REQUEST['writeStory'] = str_replace("\r", "", $_REQUEST['writeStory']);
            $writeStoryModel = new WriteStoryModel($_REQUEST['writeIdentifier'], $_REQUEST['writeTitle'],
                $_REQUEST['writeAuthor'], $_REQUEST['writeStory'], $genreIDs);
            return $writeStoryModel->addStory();
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