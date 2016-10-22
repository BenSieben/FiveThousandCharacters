<?php
namespace cs174\hw3\views\helpers;

/**
 * Class WriteErrorHelper
 * @package cs174\hw3\views\helpers
 *
 * Helper which helps render error
 * messages in the Write Something view
 * when the user gives bad input
 */
class WriteErrorHelper extends Helper {

    /**
     * Renders a HTML paragraphs of error messages in $data
     * @param $data Array<String> of error messages to create paragraphs for
     * @return String|false HTML code of error messages om $data array,
     * or false if $data is not a valid array
     */
    public function render($data) {
        if(!isset($data) || !is_array($data)) {
            return false;
        }
        $html = "";
        foreach($data as $message) {
            $html .= "    <p class=\"err\">$message</p>\n";
        }
        return $html;
    }
}
?>