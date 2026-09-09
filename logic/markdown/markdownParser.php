<?php
include "Rules/parsingHeader.php";
include "Rules/parsingTextStyle.php";
include "Rules/parsingMedia.php";

class markdownParser {
    public function Parse($text){
        $safeText = htmlspecialchars($text);

        $header = new headingParser();
        $parsedText = $header->parse($safeText);

        $textStyle = new parsingTextStyle();
        $parsedText = $textStyle->parse($parsedText);

        $media = new mediaParser();
        $parsedText = $media->parse($parsedText);

        return $parsedText;
    }
}
?>