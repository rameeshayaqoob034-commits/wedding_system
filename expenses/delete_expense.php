<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wedding_system";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) { die("Connection failed: ".$conn->connect_error); }

if(!isset($_GET['event_id'])){ die("No Event ID provided."); }

$event_id = $_GET['event_id'];

$delete_sql = "DELETE FROM expenses WHERE event_id='$event_id'";
if($conn->query($delete_sql)===TRUE){
    echo "<script>alert('Expense Deleted Successfully'); window.location='expenses_list.php';</script>";
} else {
    echo "Error: ".$conn->error;
}

$conn->close();
?>