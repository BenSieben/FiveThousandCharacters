<?php
namespace cs174\hw3\controllers;
use Config;

/**
 * Class Controller
 * @package cs174\hw3\controllers
 *
 * Superclass for any class used
 * as a Controller for the website
 */
class Controller {

    /**
     * This function will look at current values
     * in PHP super globals such as $_REQUEST and
     * $_SESSION to determine which Controller subclass
     * to call to handle the forms
     */
    public function processForms() {
        if(isset($_REQUEST['c'])) { // c is name of Controller to call
            if(isset($_REQUEST['m'])) { // m is name of Controller method to call
                // create certain Controller and call certain
                //   method based on c and m

                if(strcmp($_REQUEST['c'], 'LandingController') === 0) { // use LandingController
                    $lc = new LandingController();
                    if(strcmp($_REQUEST['m'], 'processForms') === 0) { // use processForms method
                        $lc->processForms();
                    }
                }
                else if(strcmp($_REQUEST['c'], 'ReadController') === 0) { // use ReadController
                    $rc = new ReadController();
                    if(strcmp($_REQUEST['m'], 'processForms') === 0) { // use processForms method
                        $rc->processForms();
                    }
                }
                else if(strcmp($_REQUEST['c'], 'WriteController') === 0) { // use WriteController
                    $wc = new WriteController();
                    if(strcmp($_REQUEST['m'], 'processForms') === 0) { // use processForms method
                        $wc->processForms();
                    }
                }
            }
        }
        else { // if $_REQUEST['c'] is not set, then show default landing page
            header("Location:" . Config::URL_TO_INDEX . "?c=LandingController&m=processForms");
        }
    }
}
?>