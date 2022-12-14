<?php
    $connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");

    if (!$connection) {
        die('MySQL connection error');
    }

    function SanitizeInput($connection, string $input) {
        return mysqli_real_escape_string($connection, $input);
    }
?>