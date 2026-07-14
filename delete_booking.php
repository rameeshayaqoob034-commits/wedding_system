<?php
$conn = new mysqli("localhost", "root", "", "wedding_system");
if($conn->connect_error){ die("Connection failed: ".$conn->connect_error); }

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id <= 0){ die("Invalid Booking ID. <a href='booking_list.php'>Back to List</a>"); }

$conn->query("DELETE FROM bookings WHERE id='$id'");

header("Location: booking_list.php");
exit;
?>