<?php
namespace cs174\hw3\views\elements;

/**
 * Class H3TitledOrderedListElement
 * @package cs174\hw3\views\elements
 *
 * Creates HTML code for top ten ordered
 * lists, given data to list out
 */
class H3TitledOrderedListElement extends Element {

    /**
     * Creates a new TopTenOrderedListElement
     * @param \cs174\hw3\views\View $view reference to the View which is using this element
     */
    public function __construct($view) {
        parent::__construct($view);
    }

    /**
     * Creates an ordered list with an h3 HTML header
     * for the list and then the ordered list itself
     * @param $listName String the h3 tag to place above the ordered list
     * @param $data Array<String> items to list out in the ordered list
     * @return String|false HTML code of ordered list of $data array with an h3 header $listName,
     * or false if $data is not a valid array or $listName is not a valid string
     */
    public function render($listName, $data) {
        if(!isset($data) || !is_array($data)) {
            return false;
        }
        if(!isset($listName) || !is_string($listName)) {
            return false;
        }
        $listHTML = "    <h3>$listName</h3>\n    <ol>";
        $listItemHelper = new \cs174\hw3\views\helpers\ListItemHelper();
        $listHTML .= $listItemHelper->render($data);
        $listHTML .= "    </ol>\n";
        return $listHTML;
    }
}
?>