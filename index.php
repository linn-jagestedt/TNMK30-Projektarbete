<!DOCTYPE html>

<html lang ="sv">   
    <head>
        <title>Legobase</title>
        <meta charset="utf-8">
        <link href="style.css" media="screen" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    </head>

    <body>
        <main class="index_main">
            <div class='bakgrund'>
                <img src='https://t3.ftcdn.net/jpg/02/66/56/76/360_F_266567677_5ZcfjBF5nsk6UwJqhhmwuZYIB6io1p1w.webp' alt="lego">
            </div>
            <div><h1 class='titel'>Legobase</h1></div>
            <div class='sokfalt'>
                <form action="searchResult.php" method="get">
                    <label calss='sok-text'><p>Search</p></label>
                    <input type="text" name="searchTerm" class='sok' placeholder=" Find a brick...">

                </form>
            </div>
            <div class="push"></div>
        </main>
    </body>

    <?php include("footer.php"); ?>
</html>  