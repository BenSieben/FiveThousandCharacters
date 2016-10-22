<?php
namespace cs174\hw3\controllers;
use cs174\hw3\models\RatingAdderModel;
use cs174\hw3\models\ReadStoryModel;
use cs174\hw3\views\Read;

/**
 * Class LandingController
 * @package cs174\hw3\controllers
 *
 * Controller for read View
 * of Five Thousand Characters website
 */
class ReadController extends Controller {

    /**
     * Looks at current values in PHP super globals
     * such as $_REQUEST and $_SESSION to draw the read
     * View and select Model(s) to use for the View
     */
    public function processForms() {
        $view = new Read();
        $data = $this->setUpViewData();
        $view->render($data);
    }

    /**
     * Creates an array of data to pass on to the Read view
     * to properly show the web page
     * @return array data which the Read view needs to fully
     * load the page
     */
    private function setUpViewData() {
        $data = [];

        // TODO sanitize form data

        // we must query database for story information based off of the story ID which (should)
        //   be in the URL for any Read view as "?sID=<some story ID>"
        //   also, return null if the link is "bad" for any reason (no sID specified / bad sID specified)
        if(!isset($_REQUEST['sID'])) {
            return null; // return null if sID is not set, as that makes displaying the normal read page impossible
        }

        // check if the user just submitted their rating for this story
        //   and if so, we must add the rating to the database
        if(isset($_REQUEST['rating']) && !isset($_SESSION['sID'])) {
            $_SESSION[$_REQUEST['sID']] = $_REQUEST['rating'];
            // use RatingAdderModel to add the user's rating to the database
            $ratingAdderModel = new RatingAdderModel($_REQUEST['sID'], intval($_SESSION[$_REQUEST['sID']]));
            $addRatingRequest = $ratingAdderModel->addStoryRating();
            if(!$addRatingRequest) {
                unset($_SESSION[$_REQUEST['sID']]); // if adding the rating failed, unset $_SESSION[$_REQUEST['sID']]
            }
            // nothing extra to do if adding the rating was successful
        }

        $readStoryModel = new ReadStoryModel($_REQUEST['sID']);
        $result = $readStoryModel->readStory(); // try to read the story based on given sID

        if(!$result) {
            return null; // return null if sID does not match any story in the database, as that makes displaying the normal read page impossible
        }

        // now we can analyze query to extract relevant data to pass along to the Read view
        foreach($result as $row) {
            // there should only be a single row in the query result; this foreach is to more easily extract the data of the story tuple
            $data['sID'] = $_REQUEST['sID'];
            $data['title'] = $row['title'];
            $data['author'] = $row['author'];
            $data['submitTime'] = $row['submitTime'];
            $data['avgRating'] = $row['avgRating'];
            // avgRating will be null if story is unrated, so change it to say 'Unrated' to tell user it has not been rated yet
            if($data['avgRating'] === null) {
                $data['avgRating'] = '(Unrated)';
            }
            $data['content'] = preg_split("/\n\n/", $row['content']); // preg split the content of the story by 2 newline characters (for making paragraphs)
            // last part of data is to check if user has rated this story or not, which will change how Read should appear to user
            if(isset($_SESSION[$_REQUEST['sID']])) {
                $data['userRating'] = $_SESSION[$_REQUEST['sID']]; // give back rating user gave to this story if it exists in the session
            }
            else {
                $data['userRating'] = '0'; // use '0' as value to indicate this story has not been rated by this user
            }
        }
        return $data;
    }
}
?>