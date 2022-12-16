<?php
    // Start the session to store variables across webpages
    session_start();
    
    // Create a connnection to the database
    $connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
    
    // If no connection was made, return error
    if (!$connection) {
        die('MySQL connection error');
    }
    
    function SanitizeInput($connection, string $input) 
    {
        $input = mysqli_real_escape_string($connection, $input);
        return trim($input);
    }
?>