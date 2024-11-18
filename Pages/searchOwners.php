<?php 
    $page_title = " Add a New Owner";
    echo "<link rel='stylesheet' href='../Components/searchownerStyle.css'>";
    echo "<link rel='stylesheet' href='../Components/menuStyle.css'>";
    include("../Components/header.php");
    include("../Components/pagesmenu.php");

    if(isset($_POST['search_buttn'])) {
        require_once ("../db_connect.php");

        function cleanData ($connection,$entry){
            return mysqli_real_escape_string($connection, trim($entry));
        }

        //Left Join used incase an Owner managed to be registered with no phone record
        $searchOwnerQry = "SELECT owner.OwnerID, CONCAT(owner.FirstName, ' ', owner.LastName) AS Name, owner.Email, phone.Phone FROM owner LEFT JOIN phone ON owner.OwnerID = phone.OwnerID" ;
        $invalidsearch = 0;

        if(!empty($_POST['OwnerID'])) {
            $id = cleanData($conn, $_POST['OwnerID']);
            $searchOwnerQry = $searchOwnerQry." WHERE owner.OwnerID LIKE '%". $id."%'";
        } else{
            $searchOwnerQry = $searchOwnerQry." WHERE owner.OwnerID IS NOT NULL";
            $invalidsearch++;
        }
        
        if(!empty($_POST['Firstname'])) {
            $firstname = cleanData($conn, $_POST['Firstname']);
            $searchOwnerQry = $searchOwnerQry." AND owner.FirstName LIKE '%". $firstname."%'";
        } else{
            $invalidsearch++;
        }

        if(!empty($_POST['Lastname'])) {
            $lastname = cleanData($conn, $_POST['Lastname']);
            $searchOwnerQry = $searchOwnerQry." AND owner.LastName LIKE '%". $lastname."%'";
        } else {
            $invalidsearch++;
        }

        if(!empty($_POST['Phone'])) {
            $phone = cleanData($conn, $_POST['Phone']);
            $searchOwnerQry = $searchOwnerQry." AND phone.Phone ='". $phone."'";
        } else {
            $invalidsearch++;
        }

        if(!empty($_POST['Email'])) {
            $email = cleanData($conn, $_POST['Email']);
            $searchOwnerQry = $searchOwnerQry." AND owner.Email LIKE '%". $email."%'";
        } else {
            $invalidsearch++;
        }
       
        if ($invalidsearch == 5) {
            echo "<div class='error'";
            echo "<h1>No Fields Given For Search</h1>";
            echo "<p>Please enter search term into at least one field!</p>";
            echo "</div>";
        } else{
                $searchOwnerQry = $searchOwnerQry." ORDER BY LastName ASC";
            

                
                $searchOwner = mysqli_query($conn, $searchOwnerQry);
                $searchMatches = mysqli_num_rows($searchOwner);

                if ($searchMatches > 0) { // If owner(s) found
                    echo "<p align='center'><b>".$searchMatches."</b> Owner matches found.</p>";

                    echo "<table border = '0' <tr><th>OwnerID</th><th>Name</th><th>Email</th><th>Phone</th><th>Tools</th></tr>";
                    // Fetch and print all the recordw
                    while($row = mysqli_fetch_array($searchOwner)) {
                    echo "<tr>";
                    echo "<td>" . $row['OwnerID'] . "</td>";
                    echo "<td>" . $row['Name'] . "</td>";
                    echo "<td><a href='mailto:".$row['Email']."'>" . $row['Email'] . "</a></td>";
                    echo "<td>" . $row['Phone'] . "</td>";
                    echo "<td><a href='./editOwners.php?OwnerID=".$row['OwnerID']."'> Edit </td>";
                    echo "</tr>";
                    }
                    echo "</table>";
                    mysqli_free_result($searchOwner);
                    mysqli_close($conn);
                } else { // If it did not run OK.
                    echo "<div class='error'";
                    echo '<h1>Search Error</h1>
                    <p>Owner could not be found with given search terms. Please try again.</p>';
                    echo "</div>";
                }
        }
       
    }
    


?>

<html>
    <body>
        <h2 id='actioncall'>Search for an owner using the fields below:</h2>
        <form id= "OwnerForm" action = "./searchOwners.php" method="post">
            <div id = ownerIDSubmit>
                <p>OWNER ID: </p>
                <input type = "text" name = "OwnerID" size = "30" placeholder = " Search ID of an Owner ">
            </div>
            <div id = firstN_Submit>
                <p>FIRST NAME:  </p>
                <input type = "text" name = "Firstname" size = "30" placeholder = " Search By first name">
            </div>
            <div id = lastN_Submit>
                <p>LAST NAME:  </p>
                <input type = "text" name = "Lastname" size = "30" placeholder = " Search by last name">
            </div>
            <div id = phoneSubmit>
                <p>PHONE NUMBER:  </p>
                <input type = "text" name = "Phone" size = "30" placeholder = " Search by phone number">
            </div>
            <div id = emailSubmit>
                <p>EMAIL:  </p>
                <input type = "text" name = "Email" size = "30" placeholder = " Search by enter email">
            </div>
            <div id = "submit_area">
                <input type = "submit" name = "search_buttn" value = "Search">
                <input type = "reset" name = "rst_buttn" value = "Clear Form">
            </div>
            <?php include("../Components/backButton.php"); ?>
        </form>
    </body>
</html>