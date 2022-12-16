<?php
    session_start();
    $connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
    
    if (!$connection) {
        die('MySQL connection error');
    }
    
    function SanitizeInput($connection, string $input) 
    {
        $input = mysqli_real_escape_string($connection, $input);
        return trim($input);
    }
?>