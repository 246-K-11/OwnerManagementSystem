<?php 
    $page_title = "Add a New Owner";
    echo "<link rel='stylesheet' href='../Components/registerownerStyle.css'>";
    echo "<link rel='stylesheet' href='../Components/menuStyle.css'>";
    include("../Components/header.php");
    include("../Components/pagesmenu.php");
    
    

    if(isset($_POST['submit_buttn'])) {
        $randOwnerID = rand(0,9999);
        require_once ("../db_connect.php");

        //Code to prevent form from generating dupilcate ID for owner
        $idQry = "SELECT OwnerID FROM owner";
        $idResults = mysqli_query($conn, $idQry);

        while($row=mysqli_fetch_array($idResults)){
            if ($row['OwnerID'] = $randOwnerID) {
                $randOwnerID = rand(0,9999);
            }
        }
        
        function cleanData ($connection,$entry){
            return mysqli_real_escape_string($connection, trim($entry));
        }

        if(empty($_POST['Firstname'])) {
            echo "<div class='error'>";
            echo "<h1>Empty First Name</h1>";
            echo "<p>Error: No first name given</p>";
            include("../Components/backButton.php");
            echo "</div>";
            exit();
        } else{
            $Firstname = cleanData($conn, $_POST['Firstname']);
        }
       
        if(empty($_POST['Lastname'])) {
            echo "<div class='error'>";
            echo "<h1>Empty Last Name</h1>";
            echo "<p>Error: No last name given</p>";
            include("../Components/backButton.php");
            echo "</div>";
            exit();
        } else{
            $Lastname = cleanData($conn, $_POST['Lastname']);
        }

        $Email = cleanData($conn, $_POST['Email']); //Email can be blank

        $phoneArry = array();
        if(!empty($_POST['Phone1'])){
            $phoneArry[] = cleanData($conn, $_POST['Phone1']);
        }
        if(!empty($_POST['Phone2'])){
            $phoneArry[] = cleanData($conn, $_POST['Phone2'] );
        }
        if(!empty($_POST['Phone3'])){
            $phoneArry[] = cleanData($conn, $_POST['Phone3']);
        }

        if(sizeof($phoneArry) < 1) {
            echo "<div class='error'>";
            echo "<h1>No Contact Details</h1>";
            echo "<p>Error: No contact details given</p>";
            mysqli_close($conn);
            include("../Components/backButton.php");
            echo "</div>";
            exit();
        } else { //Insert Owner Information and their contact details as long as contact details are present 

            for ($i=0; $i < sizeof($phoneArry) ; $i++) { //check if each phone number is unique to the table and in the right format
                $Phone = $phoneArry[$i];
                //Ensure no duplicate numbers are added
                $checkDupQry = "SELECT Phone from phone where Phone='".$Phone."'";
                $checkDup = mysqli_query($conn, $checkDupQry);
                $num = mysqli_num_rows($checkDup);

                if ($num == 0 && str_contains($Phone, '-')) { //check for duplicates and ensure number is in the correct format
                    continue;
                    mysqli_free_result($checkDup);
                } else {
                    echo "<div class='error'>";
                    echo "<h1>Duplicate Phone Number</h1>";
                    echo "<p>Error: Duplicate phone number found in phone number table or number not in the correct format!</p>";
                    mysqli_close($conn);
                    include("../Components/backButton.php");
                    echo "</div>";
                    exit();
                }

            }
            $newOwnerQry = "INSERT INTO owner (OwnerID, FirstName, LastName, Email) VALUES (".$randOwnerID.",'".$Firstname."', '".$Lastname."', '".$Email."')";
            $newOwner = mysqli_query($conn, $newOwnerQry);

            if (!$newOwner) { // If owner is created succesfully // If it did not run OK.
                echo "<div class='error'>";
                echo "<h1>Owner Creation Error</h1>
                <p class='error'>New owner could not be registered due to a system error. Please contact IT support.</p>"; // Public message.
                mysqli_close($conn);
                echo "</div>";
                exit();
            } else{
                for ($j=0; $j < sizeof($phoneArry) ; $j++) { 
                    $Phone = $phoneArry[$j];
                    $newContactQry = "INSERT INTO phone (OwnerID, Phone) VALUES (".$randOwnerID.", '".$Phone."')";
                    $newContact = mysqli_query($conn, $newContactQry);
                }  
            }

           
        }
        mysqli_close($conn);
        echo "<div id='Success'>";
        echo "<h1>Created!</h1>";
        echo "<p>New Owner Registered Sucessfully!</p>";	
        echo "<p> Name: ".$Firstname." ".$Lastname." , OwnerID: ".$randOwnerID."";
        echo "</div>";

    }   


?>

<html>
    <body>
        <h2>Register a New Owner with The Haven</h2>
        <p id='actionCall'>Submit Information Below:</p>
        <form id= "OwnerForm" action = "./registerOwner.php" method="post">
            <div id = nameSubmit>
                <p>NAME: </p>
                <input type = "text" name = "Firstname" size = "30" placeholder = " Please enter first name ">
                <input type = "text" name = "Lastname" size = "30" placeholder = " Please enter last name ">
            </div>
            <div id = detailsSubmit>
                <p>CONTACT DETAILS:  </p>
                <input type = "text" name = "Phone1" size = "30" placeholder = " Please enter phone number(Format: ###-####)">
                <input type = "text" name = "Phone2" size = "30" placeholder = " Please enter additional phone number(Format: ###-####)">
                <input type = "text" name = "Phone3" size = "30" placeholder = " Please enter additional phone number(Format: ###-####)">
            </div>
            <div id = emailSubmit>
                <p>EMAIL(Optional):  </p>
                <input type = "text" name = "Email" size = "30" placeholder = " Please enter email">
            </div>
            <div id = "submit_area">
                <input type = "submit" name = "submit_buttn" value = "Create New Owner">
                <input type = "reset" name = "rst_buttn" value = "Clear Form">
            </div>
        </form>
        <?php include ("../Components/backButton.php")?>
    </body>
</html>