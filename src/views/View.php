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
    public abstract function render($data);
}
?>