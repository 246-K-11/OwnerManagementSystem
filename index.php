<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Management System</title>
    <link rel="icon" type="image/xicon" href="./Images/paw.png">
    <link rel="stylesheet" href="./Components/indexStyle.css">
    <link rel="stylesheet" href="./Components/menuStyle.css">
</head>

<body>
    <h1>Welcome to The Haven's Owner Management System</h1>
    <div id="menu">
     <p>Select a system operation below:</p>
     <?php include("./Components/pagesmenu.php") ?>
    </div>

    <div id="statscontainer">
    <?php  
        require_once("./db_connect.php");
     ?>
     <?php 
        $totalOwnersQry = ("SELECT COUNT(OwnerID) as OwnerCount FROM owner");
        $totalOwners = mysqli_query($conn, $totalOwnersQry);
        $row = mysqli_fetch_array($totalOwners);
        echo "<p class = 'stats'> Number of Owners Registered: " . $row['OwnerCount'] . "</p>\n";

        mysqli_free_result($totalOwners);
     ?>
     <?php 
        $totalPhonesQry = ("SELECT COUNT(OwnerID) as PhoneCount FROM phone");
        $totalPhones = mysqli_query($conn, $totalPhonesQry);
        $row = mysqli_fetch_array($totalPhones);
        echo "<p class = 'stats'> Number of Phone Numbers Registered: " . $row['PhoneCount'] . "</p>\n";

        mysqli_free_result($totalPhones);
     ?>
     </div>
</body>
</html>