<?php
namespace cs174\hw3\views\helpers;

/**
 * Class SelectOptionHelper
 * @package cs174\hw3\views\helpers
 *
 * Helper for view which gives back HTML
 * code for making  options (to put
 * in a select HTML element) based on a
 * given array of data to list out
 */
class SelectOptionHelper extends Helper {

    /**
     * Renders an HTML option collection of whatever data is in $data
     * @param $data Array<String> of list elements to create options for
     * @return String|false HTML code of options that were in the given $data array,
     * or false if $data is not a valid array
     */
    public function render($data) {
        if(!isset($data) || !is_array($data)) {
            return false;
        }
        $listHTML = "";
        foreach($data as $elm) {
            $listHTML .= "            <option>$elm</option>\n";
        }
        return $listHTML;
    }
}
?>