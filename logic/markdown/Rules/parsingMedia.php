<?php
class mediaParser{
    public function parse($text){
        $text = $this->link($text);

        return $text;
    }

    private function link($text){
        return preg_replace_callback(
            '/\[(.*?)\]\((.*?)\)/',

            function($match){
                $label = $match[1];

                $url = $match[2];

                if (
                    !str_starts_with($url, "http://") && 
                    !str_starts_with($url, "https://")
                ){
                    return $label;
                }

                return '<a href="' . $url . '" target="_blank">' .
                    $label .
                    '</a>';
            },

            $text
        );
    }
}

?>