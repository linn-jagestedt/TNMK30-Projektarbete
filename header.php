<header>
    <div class="header_flex">
        <div class="logo">
            <span><a href="./">Legobase</a></span>
        </div>
        <div class="searchBox">
            <form action="searchResult.php" method="get">
                <label id="searchBox" class="search_label">Search</label> <!-- Ändrade "for" till "id" -->
                <input type="text" name="searchTerm" value="<?php echo($_SESSION['searchTerm']) ?>">
            </form>
        </div>
    </div>
</header>