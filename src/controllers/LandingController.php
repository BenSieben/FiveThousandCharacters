<?php
namespace cs174\hw3\controllers;
use cs174\hw3\models\GenreModel;
use cs174\hw3\models\TopTenModel;
use cs174\hw3\views\Landing;

/**
 * Class LandingController
 * @package cs174\hw3\controllers
 *
 * Controller for landing View
 * of Five Thousand Characters website
 */
class LandingController extends Controller {

    /**
     * Looks at current values in PHP super globals
     * such as $_REQUEST and $_SESSION to draw the landing
     * View and select Model(s) to use for the View
     */
    public function processForms() {
        $view = new Landing();
        $data = $this->setUpViewData();
        $view->render($data);
    }

    /**
     * Creates an array of data to pass on to the Landing view
     * to properly show the web page
     * @return array data which the Landing view needs to fully
     * load the page
     */
    private function setUpViewData() {
        $data = [];

        // TODO sanitize form data

        // first data: phraseFilter
        if(isset($_REQUEST['phraseFilter'])) { // add phraseFilter to session if it was sent (via Landing page form)
            $_SESSION['phraseFilter'] = $_REQUEST['phraseFilter'];
        }
        if(isset($_SESSION['phraseFilter'])) { // add phraseFilter to data if it is set in the session
            $data['phraseFilter'] = $_SESSION['phraseFilter'];
        }
        else { // add empty phraseFilter if it is not set in the session
            $data['phraseFilter'] = '';
        }

        // second data: genre to select by default
        if(isset($_REQUEST['genre'])) { // add genre to session
            $_SESSION['genre'] = $_REQUEST['genre'];
        }
        if(isset($_SESSION['genre'])) { // add genre to data if it is set in the session (use array for SetOptionHelper compatibility)
            $data['genre'][0] = $_SESSION['genre'];
        }
        else { // add empty genre if it is not set in the session (use array for SetOptionHelper compatibility)
            $data['genre'][0] = '';
        }

        // third data: genreList (array of all genres to list in the select drop down on the landing page)
        $genreModel = new GenreModel();
        $result = $genreModel->getListOfGenres();
        $data['genreList'] = ['All Genres']; // 'All Genres' is the first option in the select drop down
        if($result !== false) {
            foreach($result as $row) { // loops through each tuple of result relation
                array_push($data['genreList'], $row['name']); // we want to add all 'name' column values from Genre relation to the genreList
            }
        }

        // for all top ten lists (made below), we need to set up the top ten model first
        $topTenTitleFilter = (strcmp($data['phraseFilter'], '') === 0) ? null : $data['phraseFilter'];
        $topTenGenreID = null;
        if(strcmp($data['genre'][0], '') !== 0 && strcmp($data['genre'][0], 'All Genres') !== 0) {
            $r = $genreModel->getGenreNameID($data['genre'][0]);
            foreach($r as $tuple) {
                // result should only be one tuple; this is used to simplify getting result data
                $topTenGenreID = $tuple['gID'];
            }
        }
        $topTenModel = new TopTenModel($topTenTitleFilter, $topTenGenreID === null ? null : intval($topTenGenreID));

        // fourth data: top ten rated titles
        $result = $topTenModel->getTopTenRated();
        $data['topTenRated'] = [];
        if($result !== false) {
            foreach($result as $row) { // loops through each tuple of result relation
                // collect sID and title for each tuple, and push each pair as a link / content pair to the topTenRated array
                $titleInfo['link'] = "?c=ReadController&m=processForms&sID=" . $row['sID'];
                $titleInfo['content'] = $row['title'];
                array_push($data['topTenRated'], $titleInfo);
            }
        }

        // fifth data: top ten viewed titles
        $result = $topTenModel->getTopTenViewed();
        $data['topTenViewed'] = [];
        if($result !== false) {
            foreach($result as $row) { // loops through each tuple of result relation
                // collect sID and title for each tuple, and push each pair as a link / content pair to the topTenViewed array
                $titleInfo['link'] = "?c=ReadController&m=processForms&sID=" . $row['sID'];
                $titleInfo['content'] = $row['title'];
                array_push($data['topTenViewed'], $titleInfo);
            }
        }

        // sixth data: top ten newest titles
        $result = $topTenModel->getTopTenNewest();
        $data['topTenNewest'] = [];
        if($result !== false) {
            foreach($result as $row) { // loops through each tuple of result relation
                // collect sID and title for each tuple, and push each pair as a link / content pair to the topTenNewest array
                $titleInfo['link'] = "?c=ReadController&m=processForms&sID=" . $row['sID'];
                $titleInfo['content'] = $row['title'];
                array_push($data['topTenNewest'], $titleInfo);
            }
        }

        return $data;
    }
}
?>