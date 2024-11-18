<?php
require_once ("../db_connect.php"); 

function removeOwner($conn, $ownerID) {
    // Delete related phone records
    $delPhonesQry = "DELETE FROM phone WHERE OwnerID=" . $ownerID;
    $delPhones = mysqli_query($conn, $delPhonesQry);

    // Delete owner record
    $delOwnerQry = "DELETE FROM owner WHERE OwnerID=" . $ownerID;
    $delOwner = mysqli_query($conn, $delOwnerQry);

    if ($delPhones && $delOwner) {
        echo "<p id = 'Success'>Owner: ".$ownerID." and their related contact information deleted!</p>";
    } else {
        return "error";
    }
}

// Only execute if POST method is used
if (isset($_REQUEST['OwnerID'])) {
    $ownerID = $_REQUEST['OwnerID'];
    $result = removeOwner($conn, $ownerID);
    echo $result;
}

mysqli_close($conn);
?>