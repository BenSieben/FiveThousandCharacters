<?php
namespace cs174\hw3\views\helpers;

/**
 * Class ListItemHelper
 * @package cs174\hw3\views\helpers
 *
 * Helper for view which gives back HTML
 * code for making list items (to put in a
 * HTML list) based on a given array of data
 * to list out
 */
class ListItemHelper extends Helper {

    /**
     * Renders an HTML list item collection of whatever data is in $data
     * @param $data Array<String> of list elements to create list items for
     * @return String|false HTML code of list items that were in the given $data array,
     * or false if $data is not a valid array
     */
    public function render($data) {
        if(!isset($data) || !is_array($data)) {
            return false;
        }
        $listHTML = "";
        foreach($data as $elm) {
            $listHTML .= "        <li>$elm</li>\n";
        }
        return $listHTML;
    }
}
?>