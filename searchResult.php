<!DOCTYPE html>

<?php 
    function SanitizeInput(string $input) {
        //Kod som tar bort mellanslag i början och slut

        //Kod som skyddar mot databas attacker

        return $input;
    }

    function DisplayItem(string $partName, array $images) {
        // Kod som använder datan i de olika tabellerna för att returnerar en 
        // sträng av html.
        return "";
    }
    
    function GetItemData($connection) 
    {
        // Hämta alla bitar vars namn matchar $_GET['searchTerm'];
        $part_result = mysqli_query($connection, "");

        // $imagesByPartName['partName'] = ...
        $imagesByPartName = null;

        while ($part_row = mysqli_fetch_array($part_result)) 
        {
            // Sök i inventory efter alla bitar med samma ItemID och distinkta färger
            $inventory_result = mysqli_query($connection, "");

            while ($inventory_row = mysqli_fetch_array($inventory_result)) 
            {
                //Sök i de nya tabellerna med info från inventory_row
                $color_result = mysqli_query($connection, "");
                $color_row = mysqli_fetch_array($color_result);

                $image_result = mysqli_query($connection, "");
                $image_row = mysqli_fetch_array($image_result);      
            }
        }

        return $imagesByPartName;
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
                $connection = null;

                $imagesByPartName = GetItemData($connection);

                foreach ($imagesByPartName as $partName => $images) 
                {
                    DisplayItem($partName, $images);
                }
                
            ?>
        </div>

        <?php include("footer.php"); ?>
    </body>
</html>
