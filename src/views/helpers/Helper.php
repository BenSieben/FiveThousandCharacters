<?php
namespace cs174\hw3\views\helpers;

/**
 * Class Helper
 * @package cs174\hw3\views\helpers
 *
 * Helpers assist View of Five Thousand Characters
 * website by looping through data to display
 * in the website
 */
abstract class Helper {
    public abstract function render($data);
}
?>