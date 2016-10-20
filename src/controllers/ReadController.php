<?php
namespace cs174\hw3\controllers;

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
        echo("Read!");
    }
}
?>