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

    private $optionsToSelect; // lets user specify an option to select in the array $data of options to render

    /**
     * Creates a new SelectOptionHelper
     * @param $optionsToSelect Array<String> (optional) specify which element(s) in $data for the render
     * function should be selected when the page loads
     */
    public function __construct($optionsToSelect) {
        $this->optionsToSelect = $optionsToSelect;
    }

    /**
     * Returns the current options to select in the rendered options (in render function)
     * @return Array<String> current options to select in rendered options (in render function)
     */
    public function getOptionsToSelect() {
        return $this->optionsToSelect;
    }

    /**
     * Changes the options to select in the rendered options to the argument String
     * @param $optionsToSelect Array<String> new options to select in the rendered options
     */
    public function setOptionsToSelect($optionsToSelect) {
        $this->optionsToSelect = $optionsToSelect;
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
            $foundSelect = false; // flag to keep track of if we already added $elm as selected option or not (so we don't duplicate $elm in output)
            foreach($this->optionsToSelect as $select) {
                if($elm === $select) { // mark option as selected if $elm equals any of the options to be selected
                    $listHTML .= "            <option selected=\"selected\">$elm</option>\n";
                    $foundSelect = true;
                    break;
                }
            }
            if(!$foundSelect) { // only add the regular $elm option if we did not already add the selected $elm option already
                $listHTML .= "            <option>$elm</option>\n";
            }
        }
        return $listHTML;
    }
}
?>