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

    private $optionToSelect; // lets user specify an option to select in the array $data of options to render

    /**
     * Creates a new SelectOptionHelper
     * @param $optionToSelect String (optional) specify which element in $data for the render
     * function should be selected when the page loads
     */
    public function __construct($optionToSelect = '') {
        $this->optionToSelect = $optionToSelect;
    }

    /**
     * Returns the current option to select in the rendered options (in render function)
     * @return String current option to select in rendered options (in render function)
     */
    public function getOptionToSelect() {
        return $this->optionToSelect;
    }

    /**
     * Changes the option to select in the rendered options to the argument String
     * @param $optionToSelect String new option to select in the rendered options
     */
    public function setOptionToSelect($optionToSelect) {
        $this->optionToSelect = $optionToSelect;
    }

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
            if($elm === $this->optionToSelect) { // mark option as selected if $elm equals $this->optionToSelect
                $listHTML .= "            <option selected=\"selected\">$elm</option>\n";
            }
            else {
                $listHTML .= "            <option>$elm</option>\n";
            }
        }
        return $listHTML;
    }
}
?>