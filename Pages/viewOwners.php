<script >
    function showPhoneDetails(OwnerID) {
        let phoneRow = document.getElementById("phoneRow-" + OwnerID)
        if(phoneRow.style.display == "table-row"){ //if phone is already displayed and button is clicked, collapse the row
            phoneRow.style.display = "none";
            phoneRow.cells[0].innerHTML = "";
        } else { //getOwnerDetails call for the phone number of the owner with OwnerID
        fetch(`../Components/getOwnerDetails.php?OwnerID=${OwnerID}`)
        .then(response => response.text())
        .then(data => {
                phoneRow.style.display = "table-row";
                phoneRow.cells[0].innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
        }   
    }
</script>

<?php
    $page_title = "View Owners";
    include("../Components/header.php");
    include ("../Components/pagesmenu.php");
    require_once("../db_connect.php");

    $getOwnersQry = ("SELECT OwnerID, CONCAT(FirstName, ' ', LastName) as Name, Email FROM owner ORDER BY LastName");
    $getOwners = mysqli_query($conn, $getOwnersQry);
    $ownersNum = mysqli_num_rows($getOwners);
    echo "<link rel='stylesheet' href='../Components/menuStyle.css'>";
    echo "<link rel='stylesheet' href='../Components/viewownersStyle.css'>";

    echo "<p align='center'><b>".$ownersNum."</b> Owners Registered</p>";
    echo "<div id='ownerView'>";
    echo "<table border='0' <tr><th>OwnerID</th><th>Name</th><th>Email</th><th>Tools</th></tr>";
        // Fetch and print all the records
        while($row = mysqli_fetch_array($getOwners)) {
        echo "<tr>";
        echo "<td>" . $row['OwnerID'] . "</td>";
        echo "<td>" . $row['Name'] . "</td>";
        echo "<td><a href='mailto:".$row['Email']."'>" . $row['Email']. "</a></td>";
        echo "<td><button onclick='showPhoneDetails(".$row['OwnerID'].")'>View Contact Details</button>, <a href='./editOwners.php?OwnerID=".$row['OwnerID']."'> Edit</td>";
        echo "</tr>";
        echo "<tr id='phoneRow-". $row['OwnerID'] . "' style='display: none;'><td colspan='3'></td></tr>";
        }
        echo "</table>";
        mysqli_free_result ($getOwners); // Free up the resources
    echo "</div>";
        include("../Components/backButton.php");
?>
