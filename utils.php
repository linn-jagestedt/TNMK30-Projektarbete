<?php 
    function SanitizeInput(string $input) {
        //Kod som tar bort mellanslag i början och slut

        //Kod som skyddar mot databas attacker

        return $input;
    }

    //copied from internet
    function seoUrl($string) {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }
?>