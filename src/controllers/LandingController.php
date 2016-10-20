<?php
namespace cs174\hw3\controllers;
use cs174\hw3\models\GenreModel;
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
        if(isset($_SESSION['genre'])) { // add phraseFilter to data if it is set in the session
            $data['genre'] = $_SESSION['genre'];
        }
        else { // add empty genre if it is not set in the session
            $data['genre'] = '';
        }

        // third data: genreList (array of all genres to list in the select drop down on the landing page)
        $genreModel = new GenreModel();
        $result = $genreModel->getListOfGenres();
        $data['genreList'] = ['All Genres']; // 'All Genres' is the first option in the select drop down
        if($result !== false) {
            foreach($result as $row) { // loops through each tuple of result relation
                foreach($row as $columnName => $columnValue) { // loops through each column in each tuple of result relation
                    if($columnName === 'name') { // we want to add all 'name' column values from Genre relation to the genreList
                        array_push($data['genreList'], $columnValue);
                    }
                }
            }
        }

        // TODO the three top 10 lists also need to be rendered

        return $data;
    }
}
?>