<?php
namespace cs174\hw3\views;
/**
 * Class View
 * @package cs174\hw3\views
 *
 * Abstract superclass for any class
 * used as the View for the website
 */
abstract class View {

    /**
     * Should render an HTML page, taking into account $data to
     * fill in certain parts of the web page
     * @param $data Array<String> data to show on the web page
     */
    public abstract function render($data);
}
?>