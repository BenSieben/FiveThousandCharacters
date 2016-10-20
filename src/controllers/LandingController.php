<?php
namespace cs174\hw3\controllers;
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
        $view->render(null);
    }
}
?>