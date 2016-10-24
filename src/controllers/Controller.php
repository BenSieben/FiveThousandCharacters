<?php
namespace cs174\hw3\controllers;
use cs174\hw3\configs\Config as Config;

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
                    else { // if method name is not recognized, default to processForms
                        $lc->processForms();
                    }
                }
                else if(strcmp($_REQUEST['c'], 'ReadController') === 0) { // use ReadController
                    $rc = new ReadController();
                    if(strcmp($_REQUEST['m'], 'processForms') === 0) { // use processForms method
                        $rc->processForms();
                    }
                    else { // if method name is not recognized, default to processForms
                        $rc->processForms();
                    }
                }
                else if(strcmp($_REQUEST['c'], 'WriteController') === 0) { // use WriteController
                    $wc = new WriteController();
                    if(strcmp($_REQUEST['m'], 'processForms') === 0) { // use processForms method
                        $wc->processForms();
                    }
                    else { // if method name is not recognized, default to processForms
                        $wc->processForms();
                    }
                }
                else if(strcmp($_REQUEST['c'], 'WriteSubmitController') === 0) { // use WriteSubmitController
                    $wsc = new WriteSubmitController();
                    if(strcmp($_REQUEST['m'], 'processForms') === 0) { // use processForms method
                        $wsc->processForms();
                    }
                    else { // if method name is not recognized, default to processForms
                        $wsc->processForms();
                    }
                }
                else { // if the controller name is not recognized, default to LandingController
                    header("Location:" . Config::URL_TO_INDEX . "?c=LandingController&m=processForms");
                }
            }
            else { // if $_REQEUST['m'] is not set, then use processForms method
                // create certain Controller based on c
                //   and call processForms method
                if(strcmp($_REQUEST['c'], 'LandingController') === 0) { // use LandingController
                    $lc = new LandingController();
                    $lc->processForms();
                }
                else if(strcmp($_REQUEST['c'], 'ReadController') === 0) { // use ReadController
                    $rc = new ReadController();
                    $rc->processForms();
                }
                else if(strcmp($_REQUEST['c'], 'WriteController') === 0) { // use WriteController
                    $wc = new WriteController();
                    $wc->processForms();
                }
            }
        }
        else { // if $_REQUEST['c'] is not set, then show default landing page
            header("Location:" . Config::URL_TO_INDEX . "?c=LandingController&m=processForms");
        }
    }
}
?>