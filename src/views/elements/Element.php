<?php
namespace cs174\hw3\views\elements;

class Element {

    public $view; // reference to view the element is currently on

    /**
     * Creates a new Element
     * @param $view \cs174\hw3\views\View reference to the View which is using this element
     */
    public function __construct($view) {
        $this->view = $view;
    }
}
?>