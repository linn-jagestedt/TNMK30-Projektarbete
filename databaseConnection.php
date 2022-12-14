<?php
    $connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");

    if (!$connection) {
        die('MySQL connection error');
    }

    function getStartIndex(int $total, int $page, int $itemsPerPage) 
    {
        $startIndex = ($page - 1) * $itemsPerPage;
        
        if ($startIndex > $total) {
            echo("<p>Page not found</p>");
            return false;
        }

        return $startIndex;
    }

    function renderPageNav(string $url, int $page, int $totalItems, int $itemsPerPage) 
    {
        echo("<div class='page-nav'>");

        if ($page > 1) {
            echo("<a href='" . $url . "&page=" . (int)($page - 1)  . "'>&#9664</a>");
        }

        echo("<span> page " . $page . " of " . ceil($totalItems / $itemsPerPage) . "</span>");

        if ($page < ceil($totalItems / $itemsPerPage)) {
            if ((int)($page * $itemsPerPage) != $totalItems) {
                echo("<a href='" . $url . "&page=" . (int)($page + 1)  . "'>&#9654</a>");
            } 
        }

        echo("</div'>");
    }

    function getItemTypeID(mysqli $connection, string $searchTerm)
    {        
        $result = mysqli_query($connection, "SELECT ItemTypeID FROM inventory WHERE ItemID = '" . $searchTerm . "' ");
        if (!$result) return "";
        
        $row = mysqli_fetch_array($result);
        if (!$row) return "";
        
        return $row["ItemTypeID"];
    }

    function getTotalParts(mysqli $connection, string $searchTerm) 
    {
        $totalItems_query = "SELECT COUNT(DISTINCT PartID) AS total FROM parts WHERE Partname LIKE '%" . $searchTerm . "%'";
        $totalItems_result = mysqli_query($connection, $totalItems_query);
        $totalItems_row = mysqli_fetch_array($totalItems_result);
        return $totalItems_row['total'];
    }

    function getImage($connection, string $colorID, string $itemID, string $itemtypeID) {
        $new_result = mysqli_query($connection, "SELECT * FROM images WHERE ItemTypeID = '" . $itemtypeID . "' AND ItemID = '" . $itemID . "' AND ColorID = '" . $colorID . "'");
        $image_row = mysqli_fetch_array($new_result);

        if($image_row) 
        {
            if ($image_row['has_jpg']) {
                $img_filetype = "jpg";
                
                if ($colorID != "") { $img_path = $itemtypeID . "/" . $colorID; }
                else { $img_path = $itemtypeID; }
            } 
            else if ($image_row['has_gif']) {
                $img_filetype = "gif";
                
                if ($colorID != "") { $img_path = $itemtypeID . "/" . $colorID; } 
                else { $img_path = $itemtypeID; }
            } 
            else if ($image_row['has_largejpg']) {
                $img_filetype = "jpg";
                $img_path =  $itemtypeID . "L";
            } 
            else if ($image_row['has_largegif']) {
                $img_filetype = "gif";
                $img_path =  $itemtypeID . "L";
            } else {
                return "./images/no_img.jpg";
            }
            return "https://weber.itn.liu.se/~stegu/img.bricklink.com/" . $img_path . "/" . $itemID . "." . $img_filetype;
        }

        // Ingen bild
        return "./images/no_img.jpg";
    }
?>