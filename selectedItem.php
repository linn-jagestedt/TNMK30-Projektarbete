<!DOCTYPE html>
<?php

    function DisplayItem($connection) 
    {
        // Hämta biten vars namn matchar $_GET['searchTerm'];
        $part_result = mysqli_query($connection, "");
        $part_row = mysqli_fetch_array($part_result);
        
        // Sök i inventory efter alla bitar med samma ItemID och distinkta färger
        $inventory_result = mysqli_query($connection, "");
        
        $images = null;

        while ($inventory_row = mysqli_fetch_array($inventory_result)) 
        {
            //Sök i de nya tabellerna med info från inventory_row
            $color_result = mysqli_query($connection, "");
            $color_row = mysqli_fetch_array($color_result);

            $image_result = mysqli_query($connection, "");
            $image_row = mysqli_fetch_array($image_result); 
            
            //lagra bilder i images
            $images = null;
        }
        
        //returnera html
        return "";
    }

    function DisplaySets($connection)
    {
        //Hämta alla setid som matchar $_GET['searchTerm'];
        $set_result = mysqli_query($connection, "");

        while ($set_row = mysqli_fetch_array($set_result)) 
        {
            //Sök i de nya tabbellerna med ItemID från set_row
            $image_result = mysqli_query($connection, "");

            //Hämta första raden från sökningen
            $image_row = mysqli_fetch_array($image_result);

            print(DisplayItem(null, null));
        }

        //returnera html
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
                $connection = null;
                DisplayItem($connection);
            ?>
        </div>

        <div class="result-list">
            <?php
                $connection = null;
                DisplaySets($connection);
            ?>
        </div>

        <?php include("footer.php"); ?>
    </body>
</html>
