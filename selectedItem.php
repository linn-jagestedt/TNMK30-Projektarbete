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
        <link href="style.css" media="screen" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
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

        <div class="item_flex_container">
            <div class="big_image_2">
                <img src="images/we52mkpw.jpg" alt="Legobit">
            </div>
            <div class="brick_text">
                <h3>Brick title</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <div class="colours">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                    <img class="single_colour" src="images/we52mkpw.jpg" alt="Legobit">
                </div>
            </div>
        </div>
        <div class="line"></div>
        <div class="sets_container">
            <div class="set">
                <img class="set_image" src="images/40522_alt1.png" alt="Legoset">
                <div class="set_text">
                    <h3>Set name</h3>
                    <p>Release year: xxxx</p><p>Part quantity: X<p>
                </div>
            </div>
            <div class="set">
                <img class="set_image" src="images/40522_alt1.png" alt="Legoset">
                <div class="set_text">
                    <h3>Set name</h3>
                    <p>Release year: xxxx</p><p>Part quantity: X<p>
                </div>
            </div>
            <div class="set">
                <img class="set_image" src="images/40522_alt1.png" alt="Legoset">
                <div class="set_text">
                    <h3>Set name</h3>
                    <p>Release year: xxxx</p><p>Part quantity: X<p>
                </div>
            </div>
            <div class="set">
                <img class="set_image" src="images/40522_alt1.png" alt="Legoset">
                <div class="set_text">
                    <h3>Set name</h3>
                    <p>Release year: xxxx</p><p>Part quantity: X<p>
                </div>
            </div>
        </div>
        <?php include("footer.php"); ?>
    </body>
</html>
