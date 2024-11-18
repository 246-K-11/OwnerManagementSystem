<?php 
    $page_title = "Edit Owner: ".$_REQUEST['OwnerID'];
    echo "<link rel='stylesheet' href='../Components/editownerStyle.css'>";
    echo "<link rel='stylesheet' href='../Components/menuStyle.css'>";
    require_once("../db_connect.php");
    include ("../Components/header.php");
    include ("../Components/pagesmenu.php");

    //Left Join used in case an Owner managed to be registered with no phone record
    $getRecordQry = "SELECT owner.OwnerID, owner.FirstName, owner.LastName, owner.Email , phone.Phone FROM owner LEFT JOIN phone ON owner.OwnerID = phone.OwnerID WHERE owner.OwnerID=".$_REQUEST['OwnerID'];
    $getRecord = mysqli_query($conn, $getRecordQry);

    $phoneRecords = array();
    $fname = "";
    $lname = "";
    while ($fields = mysqli_fetch_array($getRecord)) {
        //No need to check if the phone field is blank to assign phoneRecords[] array, the null row of the fetch_array is not set in an array.
        $phoneRecords[] = $fields['Phone'];
        if (!empty($fields['FirstName']) || !empty($fields['LastName'])) { //if firstname or lastname not empty, grab data for user
            $fname = $fields['FirstName'];
            $lname = $fields['LastName'];
            $email = $fields['Email'];
        }
    }
    mysqli_free_result($getRecord);

    if(isset($_POST['del_buttn'])) {
            echo "<script>
                let warningtxt = 'Are you sure you want to delete Owner: ".$_POST['OwnerID']."? If yes, click OK. If no, click CANCEL';
                if (confirm(warningtxt) == true) {
                    fetch(`../Components/removeOwner.php?OwnerID=".$_POST['OwnerID']."`)
                    .then(response => response.text())
                    .then(data => {
                            if (data == 'error') {
                                alert('Error deleting Owner: ".$_POST['OwnerID']."');
                            } else {
                            alert('Owner: ".$_POST['OwnerID'].", ".$_POST['Firstname']." ".$_POST['Lastname']." deleted successfully.');
                            window.location.href = './viewOwners.php'; //Go back to viewOwners 
                            }   
                    })
                    .catch(error => console.error('Error:', error));
                } else { history.back(); }
            </script>";
    }

    if(isset($_POST['edit_buttn'])) {
        function cleanData ($connection,$entry){
            return mysqli_real_escape_string($connection, trim($entry));
        }

        $invalidedit = 3;

        if(!empty($_POST['Phone1']) && str_contains($_POST['Phone1'], '-')){ //ensure phone field is not blank or incorrectly formatted before performing an Insert or Update
            $phone1 = cleanData($conn, $_POST['Phone1']);
            if(!empty($phoneRecords[0])){ //If a phone number has already been retrieved from the database for this field, perform an Update
                $updatePhone1Qry = "UPDATE phone SET Phone = '".$phone1."' WHERE Phone = '".$phoneRecords[0]."'";
                $updatePhone1 = mysqli_query($conn, $updatePhone1Qry);
            } else { //If a phone number has not been retrived from the database, insert it in as a new record
            $insertPhone1Qry = "INSERT INTO phone VALUES (".$_POST['OwnerID'].", '".$phone1."')";
            $insertPhone1 = mysqli_query($conn, $insertPhone1Qry);
            }
        } else{$invalidedit--;}

        if(!empty($_POST['Phone2']) && str_contains($_POST['Phone2'], '-')){
            $phone2 = cleanData($conn, $_POST['Phone2']);
            if(!empty($phoneRecords[1])){
            $updatePhone2Qry = "UPDATE phone SET Phone = '".$phone2."' WHERE Phone = '".$phoneRecords[1]."'";
            $updatePhone2 = mysqli_query($conn, $updatePhone2Qry);
            } else {
            $insertPhone2Qry = "INSERT INTO phone VALUES (".$_POST['OwnerID'].", '".$phone2."')";
            $insertPhone2 = mysqli_query($conn, $insertPhone2Qry);
            }
        } else{$invalidedit--;}

        if(!empty($_POST['Phone3']) && str_contains($_POST['Phone3'], '-')){
            $phone3 = cleanData($conn, $_POST['Phone3']);
            if(!empty($phoneRecords[2])){
            $updatePhone3Qry = "UPDATE phone SET Phone = '".$phone3."' WHERE Phone = '".$phoneRecords[2]."'";
            $updatePhone3 = mysqli_query($conn, $updatePhone3Qry);
            } else {
            $insertPhone3Qry = "INSERT INTO phone VALUES (".$_POST['OwnerID'].", '".$phone3."')";
            $insertPhone3 = mysqli_query($conn, $insertPhone3Qry);
            }
        } else{$invalidedit--;}

        if($invalidedit == 0) {
            echo "<div class='error'>";
            echo "<h1>Removing All Contacts</h1>";
            echo "<p>Error: At least one correctly formatted number must be entered when editing!</p>";
            include ("../Components/backButton.php");
            echo "</div>";
            mysqli_close($conn);
            exit();
        }

        //Delete all phone numbers tied to form fields posted back as empty
        $phoneFields = ['Phone1', 'Phone2', 'Phone3'];
        for ($i=0; $i < sizeof($phoneRecords) ; $i++) { 
            if(empty($_POST[$phoneFields[$i]])){
                $deletePhoneQry = "DELETE FROM phone WHERE Phone='".$phoneRecords[$i]."'";
                $deletePhone = mysqli_query($conn, $deletePhoneQry);
            }
        }
        
        $firstname = cleanData($conn, $_POST['Firstname']);
        $lastname = cleanData($conn, $_POST['Lastname']);
        $email = cleanData($conn, $_POST['Email']);

        if(empty($firstname) && empty($lastname)){
            echo "<div class='error'>";
            echo "<h1>Removing all name details!</h1>";
            echo "<p>Error: Please leave a first or last name when editing.</p>";
            include ("../Components/backButton.php");
            echo "</div>";
            mysqli_close($conn);
            exit();
        } else{
            $updateOwnerQry = "UPDATE owner SET FirstName = '".$firstname."', LastName ='".$lastname."', Email ='".$email."' WHERE OwnerID=".$_POST['OwnerID'];
            $updateOwner = mysqli_query($conn, $updateOwnerQry);
            echo "<div class='Success'>";
            echo "<h1>Edit Successful!</h1>";
            echo "<p>Owner: ".$_POST['OwnerID'].", ".$_POST['Firstname']." ".$_POST['Lastname']."</p>";
            echo "<p>Please wait 6 seconds for while the page automatically refreshes with your changes; you can also go back manually with the back button. Be sure to refresh your page to see changes!</p>";
            include("../Components/backButton.php");
            echo "</div>";
            header("refresh: 6; url =".$_SERVER['HTTP_REFERER']."");
            exit();
        }
       
    }
?>

<html>
    <body>
        <h2>Editing Owner: <?php echo $_REQUEST['OwnerID']?></h2>
        <p id = "actioncall">Submit Information Below:</p>
        <form id= "OwnerForm" action = "./editOwners.php" method="post">
            <!--Created so that when POST is sent by the form $_REQUEST['OwnerID'] will retain it's value and $_POST['OwnerID'] will be assigned the correct value-->
            <input type= "hidden" name = "OwnerID" value="<?php echo $_REQUEST['OwnerID'] ?>">
            <div id = nameSubmit>
                <p>NAME: </p>
                <input type = "text" name = "Firstname" size = "30" value = "<?php echo htmlspecialchars($fname);?>">
                <input type = "text" name = "Lastname" size = "30" value = "<?php echo htmlspecialchars($lname);?>">
            </div>
            <div id = detailsSubmit>
                <p>CONTACT DETAILS(Remove to delete contact from Database):  </p>
                <input type = "text" name = "Phone1" size = "30" value = "<?php echo isset($phoneRecords[0]) ? htmlspecialchars($phoneRecords[0]) : ''; ?>">
                <input type = "text" name = "Phone2" size = "30" value = "<?php echo isset($phoneRecords[1]) ? htmlspecialchars($phoneRecords[1]) : ''; ?>">
                <input type = "text" name = "Phone3" size = "30" value = "<?php echo isset($phoneRecords[2]) ? htmlspecialchars($phoneRecords[2]) : ''; ?>">
            </div>
            <div id = emailSubmit>
                <p>EMAIL(Remove to delete email from Database):  </p>
                <input type = "text" name = "Email" size = "30" value = "<?php echo htmlspecialchars($email)?>">
            </div>
            <div id = "submit_area">
                <input type = "submit" name = "del_buttn" value = "Delete Owner">
                <input type = "submit" name = "edit_buttn" value = "Confirm Edit">
            </div>
        </form>
    </body>
</html>
