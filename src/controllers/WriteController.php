<?php
namespace cs174\hw3\controllers;
use cs174\hw3\models\GenreModel;
use cs174\hw3\models\ReadStoryModel;
use cs174\hw3\views\Write;

/**
 * Class LandingController
 * @package cs174\hw3\controllers
 *
 * Controller for write View
 * of Five Thousand Characters website
 */
class WriteController extends Controller {

    /**
     * Looks at current values in PHP super globals
     * such as $_REQUEST and $_SESSION to draw the write
     * View and select Model(s) to use for the View
     */
    public function processForms() {
        $view = new Write();
        $data = $this->setUpViewData();
        $view->render($data);
    }

    /**
     * Creates an array of data to pass on to the Write view
     * to properly show the web page
     * @return array data which the Write view needs to fully
     * load the page
     */
    private function setUpViewData() {
        $data = [];

        // load up Config constants for max length of write something input fields to pass to the Write view
        $data['maxTitleLength'] = \Config::WS_MAX_TITLE_LENGTH;
        $data['maxAuthorLength'] = \Config::WS_MAX_AUTHOR_LENGTH;
        $data['maxIdentifierLength'] = \Config::WS_MAX_IDENTIFIER_LENGTH;
        $data['maxStoryLength'] = \Config::WS_MAX_STORY_LENGTH;

        // genreList (array of all genres to list in the select drop down on the landing page)
        $genreModel = new GenreModel();
        $result = $genreModel->getListOfGenres();
        $data['genreList'] = []; // initialize empty array for $data['genreList']
        if($result !== false) {
            foreach($result as $row) { // loops through each tuple of result relation
                array_push($data['genreList'], $row['name']); // we want to add all 'name' column values from Genre relation to the genreList
            }
        }

        // check special case where only identifier was filled, in which case
        //   we load up the story with that identifier from the database (if it exists)
        $loadData = $this->checkForLoad();
        if($loadData !== false) { // if not false, we will be loading data into Write view
            return array_merge($data, $loadData); // merge Config constants with other $loadData values to prepare the Write view
        }

        // check for data submitted to put it back in the forms again
        //   (they will be in session from WriteSubmitController)
        if(isset($_SESSION['writeTitle'])) { // check for title
            $data['writeTitle'] = $_SESSION['writeTitle'];
            unset($_SESSION['writeTitle']); // unset because we do not want the session value to persist if user leaves write view
        }
        else {
            $data['writeTitle'] = '';
        }

        if(isset($_SESSION['writeAuthor'])) { // check for author
            $data['writeAuthor'] = $_SESSION['writeAuthor'];
            unset($_SESSION['writeAuthor']); // unset because we do not want the session value to persist if user leaves write view
        }
        else {
            $data['writeAuthor'] = '';
        }

        if(isset($_SESSION['writeIdentifier'])) { // check for identifier
            $data['writeIdentifier'] = $_SESSION['writeIdentifier'];
            unset($_SESSION['writeIdentifier']); // unset because we do not want the session value to persist if user leaves write view
        }
        else {
            $data['writeIdentifier'] = '';
        }

        if(isset($_SESSION['writeGenres'])) { // check for genre(s)
            $data['writeGenres'] = $_SESSION['writeGenres'];
            unset($_SESSION['writeGenres']); // unset because we do not want the session value to persist if user leaves write view
        }
        else {
            $data['writeGenres'] = [];
        }

        if(isset($_SESSION['writeStory'])) { // check for story content
            $data['writeStory'] = $_SESSION['writeStory'];
            unset($_SESSION['writeStory']); // unset because we do not want the session value to persist if user leaves write view
        }
        else {
            $data['writeStory'] = '';
        }

        return $data;
    }

    /**
     * Checks if the write view should load data from the database or not,
     * and if so will load up $data with proper values from the database
     * @return bool|Array<String> gives back proper $data values if all
     * queries were successful and the load condition is met, or else
     * false
     */
    private function checkForLoad() {
        if(isset($_SESSION['writeIdentifier']) && strcmp($_SESSION['writeIdentifier'], '') !== 0
            && (!isset($_SESSION['writeTitle']) || strcmp($_SESSION['writeTitle'], '') === 0)
            && (!isset($_SESSION['writeAuthor']) || strcmp($_SESSION['writeAuthor'], '') === 0)
            && (!isset($_SESSION['writeGenres']) || count($_SESSION['writeGenres']) === 0)
            && (!isset($_SESSION['writeStory']) || strcmp($_SESSION['writeStory'], '') === 0)) {
            $readStoryModel = new ReadStoryModel($_SESSION['writeIdentifier']);
            unset($_SESSION['writeIdentifier']); // unset because we do not want the session value to persist if user leaves write view
            $result = $readStoryModel->editStory();
            if($result !== false) { // only load the story if the writeIdentifier is an actual story ID
                foreach($result as $row) {
                    // there should only be a single row in the query result; this foreach is to more easily extract the data of the story tuple
                    $data['writeTitle'] = $row['title'];
                    $data['writeAuthor'] = $row['author'];
                    $data['writeIdentifier'] = $row['sID'];
                    $data['writeStory'] = $row['content'];
                    // now we have to load up $data['writeGenres'] with whatever genres the story is associated with
                    // first get the genre IDs based on the story ID
                    $genreModel = new GenreModel();
                    $resultGenres = $genreModel->getGenresForStoryID($data['writeIdentifier']);
                    if($resultGenres !== false) {
                        $storyGenreIDs = [];
                        foreach($resultGenres as $genreIDRow) {
                            array_push($storyGenreIDs, $genreIDRow['gID']);
                        }
                        // then get the genre names based on genre IDs to select them in the Read view
                        $resultGenreNames = $genreModel->getGenreNames($storyGenreIDs);
                        if($resultGenreNames !== false) {
                            $data['writeGenres'] = [];
                            foreach($resultGenreNames as $genreName) {
                                array_push($data['writeGenres'], $genreName['name']);
                            }
                            return $data;
                        }
                    }
                }
            }
        }
        return false; // return false if any of the conditions failed (bad queries / improper $_REQUEST set up)
    }
}
?>