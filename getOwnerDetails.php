<?php 
    require_once("../db_connect.php");

    $getContactQry = "SELECT * FROM phone WHERE OwnerID = ".$_REQUEST['OwnerID'];
    $getContact = mysqli_query($conn, $getContactQry);

    echo "<table border='1' <tr><th>Phone Number</th></tr>";
   
    while($row= mysqli_fetch_array($getContact)){
        echo "<tr>";
        echo "<td>". $row['Phone']. "</td>";
        echo "<tr>";
    }
    echo "</table>";
    
    mysqli_free_result($getContact);
?>