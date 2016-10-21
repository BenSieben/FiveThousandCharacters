<?php
namespace cs174\hw3\controllers;
use cs174\hw3\models\GenreModel;
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

        // TODO sanitize form data

        // load up Config constants for max length of write something input fields to pass to the Write view
        $data['maxTitleLength'] = \Config::WS_MAX_TITLE_LENGTH;
        $data['maxAuthorLength'] = \Config::WS_MAX_AUTHOR_LENGTH;
        $data['maxIdentifierLength'] = \Config::WS_MAX_IDENTIFIER_LENGTH;
        $data['maxStoryLength'] = \Config::WS_MAX_STORY_LENGTH;

        // TODO update database with new story (if the requests are filled out)

        // genreList (array of all genres to list in the select drop down on the landing page)
        $genreModel = new GenreModel();
        $result = $genreModel->getListOfGenres();
        $data['genreList'] = []; // initialize empty array for $data['genreList']
        if($result !== false) {
            foreach($result as $row) { // loops through each tuple of result relation
                array_push($data['genreList'], $row['name']); // we want to add all 'name' column values from Genre relation to the genreList
            }
        }

        // TODO check special case where only identifier was filled, in which case
        //   we load up the story with that identifier from the database (if it exists)

        // check for data submitted to put it back in the forms again
        if(isset($_REQUEST['writeTitle'])) { // check for title
            $data['writeTitle'] = $_REQUEST['writeTitle'];
        }
        else {
            $data['writeTitle'] = '';
        }

        if(isset($_REQUEST['writeAuthor'])) { // check for author
            $data['writeAuthor'] = $_REQUEST['writeAuthor'];
        }
        else {
            $data['writeAuthor'] = '';
        }

        if(isset($_REQUEST['writeIdentifier'])) { // check for identifier
            $data['writeIdentifier'] = $_REQUEST['writeIdentifier'];
        }
        else {
            $data['writeIdentifier'] = '';
        }

        if(isset($_REQUEST['writeGenres'])) { // check for genre(s)
            $data['writeGenres'] = $_REQUEST['writeGenres'];
        }
        else {
            $data['writeGenres'] = '';
        }

        if(isset($_REQUEST['writeStory'])) { // check for story content
            $data['writeStory'] = $_REQUEST['writeStory'];
        }
        else {
            $data['writeStory'] = '';
        }

        return $data;
    }
}
?>