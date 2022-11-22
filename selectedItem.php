<!DOCTYPE html>
<?php
    function DisplayItem(array $inventory_row, array $color_row, array $image_row) {
        // Kod som använder datan i de olika tabellerna för att returnerar en 
        // sträng av html.

        // Bild
        // Namn
        // År

        return "";
    }

    function DisplaySet(array $set_row, array $image_row) {
        // Kod som använder datan i de olika tabellerna för att returnerar en 
        // sträng av html.

        // Bild
        // Namn
        // År

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

        <div>
            <?php
                // Kod som skapar en connection till databasen

                // Sök i tabellen efter biten som med itemID = $_GET['itemID'];
                $inventory_result = mysqli_query($connection, "");

                // Hämta första raden från sökningen
                $inventory_row = mysqli_fetch_array($inventory_result);
                
                //Sök i de nya tabbellerna med informationen från inventory
                $color_result = mysqli_query($connection, "");
                $image_result = mysqli_query($connection, "");
                
                //Hämta första raden från sökningen
                $color_row = mysqli_fetch_array($color_result);
                $image_row = mysqli_fetch_array($image_result);

                DisplayItem($inventory_row, $color_row, $image_row);
            ?>
        </div>

        <div class="result-list">
            <?php
                // Kod som skapar en connection till databasen

                //Hämta alla setid som matchar $_GET['searchTerm'];
                $set_result = mysqli_query($connection, "");

                while ($inventory_row = mysqli_fetch_array($inventory_result)) 
                {
                    //Sök i de nya tabbellerna med ItemID från inventory_row
                    $set_result = mysqli_query($connection, "");
                    $image_result = mysqli_query($connection, "");

                    //Hämta första raden från sökningen
                    $set_row = mysqli_fetch_array($set_result);
                    $image_row = mysqli_fetch_array($image_result);

                    //Skriv ut html
                    print(DisplayItem($set_row, $color_row, $image_row));
                }
            ?>
        </div>

        <?php include("footer.php"); ?>
    </body>
</html>
