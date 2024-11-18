<!-- start mysql_connect.php -->
<?php
// Make the connnection using the following variables below.
$hostname = "localhost";
$username = "root";
$database = "400007107";

$conn = mysqli_connect(hostname:$hostname, username:$username, database:$database);
if (!$conn)  {  
	die('Could not connect to database: ' .mysqli_error($conn));  
}
echo "<script> console.log('Database connection Successful') </script>";
?>