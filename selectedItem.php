<?php
    include_once("databaseConnection.php");
    include_once('utils.php');

    function renderSet(string $setname, string $quantity, string $category, string $year, string $image) 
    {
        echo("<div class='set'>\n");

            echo("<img class='set_image'" . " src='" . $image .  "' alt='lego-set'>\n");

            echo("<div class='set_text'>");

                echo("<h3>" . $setname . " (" . $category . ")</h3>\n");
                echo("<p><b>Release Date: </b>" . $year . "</p>\n");
                echo("<p><b>Quantity: </b>" . $quantity . "</p>\n");

            echo("</div>\n");

        echo("</div>\n");
    }

    function renderSets($connection, $inventoryResult) 
    {    
        while ($inventory_row = mysqli_fetch_array($inventoryResult)) 
        {
            $quantity = $inventory_row['Quantity'];            
            $setID = $inventory_row['SetID'];

            // Get set info from the sets table
            $set_query = "SELECT * FROM sets WHERE SetID = '" . $setID . "'";
            $set_result = mysqli_query($connection, $set_query);
            $set_row = mysqli_fetch_array($set_result);

            $year = $set_row['Year'];
            $setname = $set_row['Setname'];

            $category = getCategory($connection, $set_row['CatID']);

            renderSet($setname, $quantity, $category, $year, getImage($connection, "", $setID, "S"));
        }
    }

    function renderSetSection($connection) 
    {
        if (isset($_SESSION['PartID']) && isset($_SESSION['ColorID'])) {
            $partID = $_SESSION['PartID'];
            $colorID = $_SESSION['ColorID'];
        } else {
            return;
        }

        // Isset is not need because these variables are set to default values
        $page = $_SESSION['page'];
        $itemsPerPage = $_SESSION['itemsPerPage'];

        $totalItems = getTotalSets($connection, $partID, $colorID);
        $startIndex = getStartIndex($totalItems, $page, $itemsPerPage);
        
        // Select all Sets with matching partID and colorID 
        $inventoryQuery = "SELECT SetID, Quantity FROM inventory WHERE ItemID = '" . $partID . "' AND ColorID = '" . $colorID . "' ORDER BY Quantity DESC LIMIT " . $startIndex . ", " . $itemsPerPage;
        $inventoryResult = mysqli_query($connection, $inventoryQuery);
       
        if ($inventoryResult->num_rows) 
        {
            renderPageNav("selectedItem.php?PartID=" . $partID . "&ColorID=" . $colorID . "&itemsPerPage=" . $itemsPerPage, $page, $totalItems, $itemsPerPage);
            renderSets($connection, $inventoryResult);
            renderPageNav("selectedItem.php?PartID=" . $partID . "&ColorID=" . $colorID . "&itemsPerPage=" . $itemsPerPage, $page, $totalItems, $itemsPerPage);
        }
    }

    function renderItem($connection) 
    {
        if (isset($_SESSION['PartID']) && isset($_SESSION['ColorID'])) {
            $partID = $_SESSION['PartID'];
            $partname = $_SESSION['Partname'];
            $colorID = $_SESSION['ColorID'];
        } else {
            echo("<p>Part not found</p>");
            return;
        }

        $itemtypeID = getItemTypeID($connection, $partID);
        $colorname = getColor($connection, $colorID);

        // Get all the distinct colors for the given part
        $colors_query = "SELECT DISTINCT ColorID FROM inventory WHERE ItemID = '" . $partID . "' ORDER BY ColorID ASC";
        $colors_result = mysqli_query($connection, $colors_query);
        
        $images = array();
        
        // Get all links to the images of the part in alla available colors.
        if ($colors_result->num_rows > 0) 
        {
            while ($colorID_row = mysqli_fetch_array($colors_result)) {
                $temp_colorID = $colorID_row['ColorID'];
                array_push($images, [ 'colorID' => $temp_colorID, 'link' => getImage($connection, $temp_colorID, $partID, $itemtypeID) ]);
            }
        }
        else 
        { array_push($images,  [ 'colorID' => '', 'link' => getImage($connection, '%', $partID, '%') ]); } 

        // Set the large image link to the image with the given colorID
        $image_link = getLinkByColorID($images, $colorID);

        echo("<div class='big_image_2'>\n");
            echo("<img id='preview_image' src='" . $image_link . "' alt='lego-part'> \n");
        echo("</div>\n");

        echo("<div class='brick_text'>\n");

            echo("<h3>" . $partname . " (" . $colorname . ")</h3>\n");

            echo("<p>" . renderDescription($partID) . "</p>");

            renderImages($images, $partID, $colorID);

        echo("</div>\n");
    }

    function getLinkByColorID(array $images, string $colorID) {
        foreach($images as $image) {
            if ($image['colorID'] === $colorID) {
                return $image['link'];
            }
        }
        return "";
    }

    function renderDescription(string $partID)
    {
        // Read the html of the website as text
        $html_text = file_get_contents('https://brickipedia.fandom.com/wiki/Part_' . $partID);
        $lastPos = 0;

        // Loop through all <p> tags in the string
        while (($lastPos = strpos($html_text, "<p>", $lastPos)) !== false) 
        {   
            $text = substr($html_text, $lastPos);            
            $text = substr($text, 0, strpos($text, "</p>"));
            $lastPos = $lastPos + strlen("<p>");

            // If the <p> tag contains the word Part + partID, return it
            if (strpos($text, 'Part ' . $partID)) {
                // Ta bort a-tag men spara innehåll
                $text = preg_replace("/<a\s(.+?)>(.+?)<\/a>/is", "$2", $text);
                // ta bort super-text
                $text = preg_replace("/<sup\s(.+?)>(.+?)<\/sup>/is", "", $text);
                return str_replace("<p>", "", $text);
            }
        }

        return "No description found";
    }

    function renderImages($images, $partID, $colorID) {
        echo("<div class='colours'>\n");

        foreach ($images as $image) {
            $image_id = "color_" . $image['colorID'];

            echo("<a href='selectedItem.php?PartID=" . $partID . "&ColorID=" . $image['colorID'] . "' onmouseleave=\"updatePreviewImage('color_" . $colorID . "')\"" . " onmouseover=\"updatePreviewImage('" . $image_id . "')\"" . ">");
                echo("<img class='single_colour' id=\"". $image_id . "\" src='" . $image['link'] .  "' alt='lego-part'>\n");
            echo("</a>");   
        }

        echo("</div>\n");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') 
    {
        if (isset($_GET['PartID'])) 
        { 
            $_SESSION['PartID'] = SanitizeInput($connection, $_GET['PartID']);
            $_SESSION['Partname'] = getPartname($connection, $_SESSION['PartID']);
        }
        
        if (isset($_GET['ColorID'])) { $_SESSION['ColorID'] = SanitizeInput($connection,  $_GET['ColorID']); }
        
        if (isset($_GET['page'])) { $_SESSION['page'] = SanitizeInput($connection, $_GET['page']); } 
        else { $_SESSION['page'] = 1; }
       
        if (isset($_GET['itemsPerPage'])) { $_SESSION['itemsPerPage'] = SanitizeInput($connection, $_GET['itemsPerPage']); } 
        else { $_SESSION['itemsPerPage'] = 10; }
    }
?>

<!DOCTYPE html>
<html lang='en'>   
    <head>
        <title>Legobase</title>
        <meta charset="UTF-8">
        <link href="./css/style.css" media="screen" rel="stylesheet" type="text/css">
        <link href="./css/style_item.css" media="screen" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="./js/selectedItem.js" defer></script> 
    </head>

    <body>
        <?php include("header.php"); ?>
        
        <div class="breadcrumb">
            <a href="./">Home</a> / <a href="./searchResult.php?searchTerm=<?php echo($_SESSION['searchTerm'])?>"><?php echo($_SESSION['searchTerm']) ?></a> / <?php echo($_SESSION['Partname'])?>
        </div>

        <div class="item_flex_container">
            <?php renderItem($connection); ?>
        </div>

        <div class="line"></div>

        <div class='sets_container'>
            <?php renderSetSection($connection); ?>
        </div>

        <?php include("footer.php"); ?>
    </body>
</html>
