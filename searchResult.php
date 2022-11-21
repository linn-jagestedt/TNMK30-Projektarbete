<!DOCTYPE html>

<?php 
    function SanitizeInput(string $input) {
        //Kod som tar bort mellanslag i början och slut

        //Kod som skyddar mot databas attacker

        return $input;
    }

    function DisplayItem(array $color_row, array $inventory_row, array $image_row) {
        // Kod som använder datan i de olika tabellerna för att returnerar en 
        // sträng av html.

        return "";
    }
?>

<html>   
    <head>
        <title>Legobasen</title>
        <meta charset="utf-8">
    </head>

    <body>
        <?php include("header.php"); ?>

        <div class="result-list">
            <?php
                // Kod som skapar en connection till databasen

                //Hämta alla bitar som matchar $_GET['searchTerm'];
                $inventory_result =  mysqli_query($connection, "");

                while ($inventory_row == mysqli_fetch_array($inventory_result)) 
                {
                    //Sök i de nya tabbellerna med ItemID från inventory_row
                    $color_result = mysqli_query($connection, "");;
                    $image_result = mysqli_query($connection, "");;

                    //Hämta första raden från sökningen
                    $color_row = mysqli_fetch_array($color_result);
                    $image_row = mysqli_fetch_array($image_result);

                    //Skriv ut html
                    print(DisplayItem($inventory_row, $color_row, $image_row));
                }
            ?>
        </div>

        <?php include("footer.php"); ?>
    </body>
</html>
