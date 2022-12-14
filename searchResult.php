<?php 
    include_once("databaseConnection.php");
    include_once("utils.php");

    function renderImage(string $partID, array $images) {
        echo("<div class='scroll'> \n");

            if (count($images) > 3) {
                echo("<a class='scroll-button' " . "onclick=\"rotate('" . $partID . "', true)\"" . "> &#9664 </a>\n");
            }

            echo("<div class='colour-image'>\n");

                foreach ($images as $image) {
                    $image_id = $partID . "_color_" . $image['colorID'];
                    echo("<img id=\"". $image_id . "\" src='" . $image['link'] .  "' " . "onclick=\"updatePreviewImage('" . $partID ."', '" . $image_id . "')\"" . "alt='lego-part'>\n");
                }

            echo("</div>\n");

            if (count($images) > 3) {
                echo("<a class='scroll-button'" . "onclick=\"rotate('" . $partID . "', false)\"" . "> &#9654 </a>\n");
            }

        echo("</div>\n");
    }

    function renderItem(string $partName, string $partID, array $images) 
    {
        echo("<div id=\"" . $partID . "\" class='item'>\n");

            echo("<div class='image-wrapper'>\n");

                echo("<a id=\"" . $partID . "_link\"> \n");

                    echo("<img id=\"" . $partID . "_preview_image\" class='big_image'> \n");

                echo("</a> \n");

            echo("</div>\n");

            echo("<span class='brick_title'>\n");

                echo("<a>" . $partName . "</a>\n");
                    
            echo("</span>\n");

            renderImage($partID, $images);
        
        echo("</div>\n");
    }

    function renderItems($connection, $part_result) 
    {
        echo("<div class='items'>");

        // Plocka varje data-rad från $part_results
        while ($part_row = mysqli_fetch_array($part_result))
        {
            // Hämta ItemtypeID från repektive PartID från 'inventory'-tabellen
            $partID = $part_row['PartID'];
            $partName = $part_row['Partname'];

            $itemtypeID = getItemTypeID($connection, $partID);

            // Sök i inventory efter alla bitar med samma ItemID och distinkta färger
            $colors_query = "SELECT DISTINCT ColorID FROM inventory WHERE ItemID = '" . $partID . "' ORDER BY ColorID ASC";
            $colors_result = mysqli_query($connection, $colors_query);
            
            $images = array();
            
            if ($colors_result->num_rows > 0) 
            {
                while ($colorID = mysqli_fetch_array($colors_result)) {
                    array_push($images, [ 'colorID' => $colorID['ColorID'], 'link' => getImage($connection, $colorID['ColorID'], $partID, $itemtypeID) ]);
                }
            }
             else 
            {  
                array_push($images,  [ 'colorID' => '', 'link' => getImage($connection, '%', $partID, '%') ]);
            } 

            renderItem($partName, $partID, $images);
        }

        echo("</div>");
    }

    function renderPageContent($connection) 
    {
        if (isset($_SESSION['searchTerm'])) {
            $searchTerm = SanitizeInput($connection, $_SESSION['searchTerm']);
        } else {
            echo("<p>No SearchTerm given</p>");
            return;
        }

        if ($searchTerm == "") {
            echo("<p>No SearchTerm given</p>");
            return;
        }

        $page = SanitizeInput($connection, $_SESSION['page']);
        $itemsPerPage = SanitizeInput($connection, $_SESSION['itemsPerPage']);

        $totalItems = getTotalParts($connection, $searchTerm);
        $startIndex = getStartIndex($totalItems, $page, $itemsPerPage);

        // Hämta alla bitar vars namn matchar $input
        $part_query = "SELECT * FROM parts WHERE Partname LIKE '%" . $searchTerm . "%' ORDER BY LENGTH(Partname), Partname ASC LIMIT " . $startIndex . ", " . $itemsPerPage;
        $part_result = mysqli_query($connection, $part_query);

        renderPageNav("searchResult.php?searchTerm=" . $searchTerm . "&itemsPerPage=" . $itemsPerPage, $page, $totalItems, $itemsPerPage);
        renderItems($connection, $part_result);
        renderPageNav("searchResult.php?searchTerm=" . $searchTerm . "&itemsPerPage=" . $itemsPerPage, $page, $totalItems, $itemsPerPage);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') 
    {
        if (isset($_GET['searchTerm'])) { $_SESSION['searchTerm'] = $_GET['searchTerm']; }

        if (isset($_GET['page'])) { $_SESSION['page'] = $_GET['page']; } 
        else { $_SESSION['page'] = 1; }
       
        if (isset($_GET['itemsPerPage'])) { $_SESSION['itemsPerPage'] = $_GET['itemsPerPage']; } 
        else { $_SESSION['itemsPerPage'] = 50; }
    }
?>

<!DOCTYPE html>

<html lang='sv'>   
    <head>
        <title>Legobase</title>
        <meta charset="utf-8">
        <link href="./css/style.css" media="screen" rel="stylesheet" type="text/css">
        <link href="./css/style_result.css" media="screen" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="./js/searchResult.js" defer></script>
    </head>

    <body>
        <main class="result_main">
            <?php include("header.php"); ?>
            <div class="navigator_container">
                <div class="flex-container">
                    <div class="flex-item filter">
                        <div class="restrainer">
                            <titel>Filter</titel>
                            <form action="/action_page.php">
                                <label for="färg">Colour:</label>
                                <select id="färg" name="färg">
                                    <option value="röd" selected>red</option>
                                    <option value="blå">blue</option>
                                    <option value="gul">yellow</option>
                                    <option value="grön">green</option>
                                </select>
                                <input type="submit">
                            </form>
                        </div>
                    </div>

                    <div class="flex-item results case">
                        <?php renderPageContent($connection); ?>
                    </div>
                </div>
            </div>
            <?php include("footer.php"); ?>
        </main>
    </body>
</html>