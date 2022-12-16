<!DOCTYPE html>

<html lang ="sv">   
    <head>
        <title>Legobase</title>
        <meta charset="utf-8">
        <link href="./css/style.css" media="screen" rel="stylesheet" type="text/css">
        <link href="./css/style_index.css" media="screen" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        <link rel="preload" href="./images/lego2.webp" as="image" type="image/png">
        <link rel="preload" href="./images/lego2-vertical.webp" as="image" type="image/png">
    </head>

    <body>
        <main class="index_main">
            <div class='bakgrund'>

            </div>
            <div><h1 class='titel'>Legobase</h1></div>
            <div class='sokfalt'>
                <form action="searchResult.php" method="get">
                    <label class='sok-text'>Search</label>
                    <input type="text" name="searchTerm" class='sok' placeholder=" Find a brick...">
                </form>
            </div>
            <div class="push"></div>
        </main>
        <?php include("footer.php"); ?>
    </body>
</html>  
