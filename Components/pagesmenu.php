<?php 
    if(str_contains($_SERVER['PHP_SELF'], "Components") ){
        echo '<div class = "menuContainer">';
        echo '<a href="../Pages/viewOwners.php">View All Owners</a>  &#x2022  <a href="../Pages/registerOwner.php">Add a New Owner</a> &#x2022 
        <a href="../Pages/searchOwners.php">Search For a Registered Owner</a>';
        echo "</div>";
    } elseif (str_contains($_SERVER['PHP_SELF'], "Pages")) {
        echo '<div class = "menuContainer">';
        echo '<a href="./viewOwners.php">View All Owners</a>  &#x2022  <a href="./registerOwner.php">Add a New Owner</a> &#x2022 
        <a href="./searchOwners.php">Search For a Registered Owner</a>';
        echo "</div>";
    } else {
        echo '<div class = "menuContainer">';
        echo '<a href="./Pages/viewOwners.php">View All Owners</a>  &#x2022  <a href="./Pages/registerOwner.php">Add a New Owner</a>  &#x2022  
        <a href="./Pages/searchOwners.php">Search For a Registered Owner</a>';
        echo "</div>";
    }
?>
