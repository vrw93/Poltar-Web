<?php
class headingParser {
    public function parse($text){
        $text = $this->h3Parse($text);
        $text = $this->h2Parse($text);
        $text = $this->headerParse($text);

        return $text;
    }

    private function headerParse($text){
        return preg_replace(
            '/^# (.*)$/m',

            '<h2>$1</h2>',

            $text
        );
    }

    private function h2Parse($text){
        return preg_replace(
            '/^## (.*)$/m',

            '<h3>$1</h3>',

            $text
        );
    }

    private function h3Parse($text){
        return preg_replace(
            '/^### (.*)$/m',

            '<h4>$1</h4>',

            $text
        );
    }
}
?>