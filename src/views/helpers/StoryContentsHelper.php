<?php
namespace cs174\hw3\views\helpers;

/**
 * Class StoryContentsHelper
 * @package cs174\hw3\views\helpers
 *
 * Helper which can render the paragraph(s)
 * of content in a story, by being given an
 * array of Strings (one String per paragraph)
 */
class StoryContentsHelper extends Helper {

    /**
     * Renders HTML paragraphs of text based on $data
     * @param $data Array<String> of text to create paragraphs for
     * @return String|false HTML code of paragraphs that were in the given $data array,
     * or false if $data is not a valid array
     */
    public function render($data) {
        if(!isset($data) || !is_array($data)) {
            return false;
        }
        $paragraphHTML = '';
        foreach($data as $p) {
            $paragraphHTML .= "    <p>$p</p>\n";
        }
        return $paragraphHTML;
    }
}