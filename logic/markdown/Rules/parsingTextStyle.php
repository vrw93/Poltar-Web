<?php
class parsingTextStyle {
    public function parse($text){
        $text = $this->newline($text);
        $text = $this->bold($text);
        $text = $this->italic($text);
        $text = $this->underline($text);

        return $text;
    }

    private function bold($text){
        return preg_replace(
            '/\*\*(.*?)\*\*/',

            '<strong>$1</strong>',

            $text
        );
    }

    private function italic($text){
        return preg_replace(
            '/\*(.*?)\*/',

            '<em>$1</em>',

            $text
        );
    }

    private function underline($text){
        return preg_replace(
            '/__(.*?)__/',

            '<u>$1</u>',

            $text
        );
    }

    private function newline($text){
        //return nl2br($text);
        return str_replace(
            "&lt;br&gt;",
            "<br>",
            $text
        );
    }
}
?>