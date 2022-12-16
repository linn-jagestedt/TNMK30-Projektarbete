<?php 
    include_once("databaseConnection.php");
    include_once("utils.php");

    // Render the images for the given part. If there are more than 3 images, add button for the scrolll functionality
    function renderImage(string $partID, array $images) {
        echo("<div class='scroll'> \n");

            if (count($images) > 3) {
                echo("<a class='scroll-button' " . "onclick=\"rotate('" . $partID . "', true)\"" . "> &#9664; </a>\n");
            }

            echo("<div class='colour-image'>\n");

                foreach ($images as $image) {
                    $image_id = $partID . "_color_" . $image['colorID'];
                    echo("<img id=\"". $image_id . "\" src='" . $image['link'] .  "' " . "onclick=\"updatePreviewImage('" . $partID ."', '" . $image_id . "')\"" . " alt='lego-part'>\n");
                }

            echo("</div>\n");

            if (count($images) > 3) {
                echo("<a class='scroll-button'" . " onclick=\"rotate('" . $partID . "', false)\"" . "> &#9654; </a>\n");
            }

        echo("</div>\n");
    }

    function renderItem(string $partName, string $partID, array $images) 
    {
        echo("<div id=\"" . $partID . "\" class='item'>\n");

            echo("<div class='image-wrapper'>\n");

                echo("<a id=\"" . $partID . "_link\"> \n");

                    echo("<img id=\"" . $partID . "_preview_image\" class='big_image' alt='lego-part' src='./images/no_image.png'>\n");

                echo("</a> \n");

            echo("</div>\n");

            echo("<span class='brick_title'>$partName</span>\n");

            renderImage($partID, $images);
        
        echo("</div>\n");
    }

    function renderItems($connection, $part_result) 
    {
        echo("<div class='items'>");

        // Fetch every row from the msqli_result
        while ($part_row = mysqli_fetch_array($part_result))
        {
            $partID = $part_row['PartID'];
            $partName = $part_row['Partname'];

            $itemtypeID = getItemTypeID($connection, $partID);

            // Search the inventory tabel after all the parts whit the sama ItemID an distinct colors.
            $colors_query = "SELECT DISTINCT ColorID FROM inventory WHERE ItemID = '" . $partID . "' ORDER BY ColorID ASC";
            $colors_result = mysqli_query($connection, $colors_query);
            
            $images = array();
            
            // Get all links to the images of the part in alla available colors.
            if ($colors_result->num_rows > 0) 
            {   
                while ($colorID = mysqli_fetch_array($colors_result)) {
                    array_push($images, [ 'colorID' => $colorID['ColorID'], 'link' => getImage($connection, $colorID['ColorID'], $partID, $itemtypeID) ]);
                }
            } 
            else { array_push($images,  [ 'colorID' => '', 'link' => getImage($connection, '%', $partID, '%') ]); } 

            renderItem($partName, $partID, $images);
        }

        echo("</div>");
    }

    function renderPageContent($connection) 
    {
        // Reqire searchTerm to be set
        if (isset($_SESSION['searchTerm'])) {
            $searchTerm = $_SESSION['searchTerm'];
        } else {
            echo("<p>No search term given</p>");
            return;
        }

        // Require a searchTerm that is atleast 3 characters long
        if (strlen($searchTerm) < 3) {
            echo("<p>Search term too short</p>");
            return;
        }

        // Isset is not need because these variables are set to default values
        $page = $_SESSION['page'];
        $itemsPerPage = $_SESSION['itemsPerPage'];

        $totalItems = getTotalParts($connection, $searchTerm);
        $startIndex = getStartIndex($totalItems, $page, $itemsPerPage);

        // Select all parts where the the partname or partid matches the searchTerm
        $part_query = "SELECT * FROM parts WHERE Partname LIKE '%" . $searchTerm . "%' OR PartID = '" . $searchTerm . "' ORDER BY LENGTH(Partname), Partname ASC LIMIT " . $startIndex . ", " . $itemsPerPage;
        $part_result = mysqli_query($connection, $part_query);

        if ($part_result->num_rows) 
        {
            renderPageNav("searchResult.php?searchTerm=" . $searchTerm . "&itemsPerPage=" . $itemsPerPage, $page, $totalItems, $itemsPerPage);
            renderItems($connection, $part_result);
            renderPageNav("searchResult.php?searchTerm=" . $searchTerm . "&itemsPerPage=" . $itemsPerPage, $page, $totalItems, $itemsPerPage);
        } else {
            echo("<p>No parts found</p>");
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') 
    {
        if (isset($_GET['searchTerm'])) { $_SESSION['searchTerm'] = SanitizeInput($connection, $_GET['searchTerm']); }

        if (isset($_GET['page'])) { $_SESSION['page'] = SanitizeInput($connection, $_GET['page']); } 
        else { $_SESSION['page'] = 1; }
       
        if (isset($_GET['itemsPerPage'])) { $_SESSION['itemsPerPage'] = SanitizeInput($connection, $_GET['itemsPerPage']); } 
        else { $_SESSION['itemsPerPage'] = 50; }
    }
?>

<!DOCTYPE html>
<html lang='en'>   
    <head>
        <title>Legobase</title>
        <meta charset="UTF-8">
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
                <div class="breadcrumb">
                    <a href="./">Home</a> / <?php echo($_SESSION['searchTerm']);?>
                </div>
                <div class="flex-container">
                    <div class="flex-item results case">
                        <?php renderPageContent($connection); ?>
                    </div>
                </div>
            </div>
            <?php include("footer.php"); ?>
        </main>
    </body>
</html>