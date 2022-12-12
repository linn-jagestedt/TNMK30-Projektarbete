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

    function getPageButtons(string $url, int $page, int $totalItems, int $itemsPerPage) 
    {
        if ($page > 1) {
            echo("<a href='" . $url . "&page=" . (int)($page - 1)  . "'>&#9664</a>");
        }

        echo("<span> page " . $page . " of " . ceil($totalItems / $itemsPerPage) . "</span>");

        if ($page < ceil($totalItems / $itemsPerPage)) {
            if ((int)($page * $itemsPerPage) != $totalItems) {
                echo("<a href='" . $url . "&page=" . (int)($page + 1)  . "'>&#9654</a>");
            } 
        }
    }

    function getSingleValue(string $result_column, string $table, string $search_column, string $search_term, mysqli $connection)
    {        
        $result = mysqli_query($connection, "SELECT " . $result_column . " FROM " . $table . " WHERE " . $search_column . " = '" . $search_term . "' ");
        if (!$result) return "";
        
        $row = mysqli_fetch_array($result);
        if (!$row) return "";
        
        return $row[$result_column];
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