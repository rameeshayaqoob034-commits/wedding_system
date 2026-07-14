<?php
// Database connection details
$servername = "localhost";  // XAMPP me hamesha localhost
$username = "root";         // default XAMPP username
$password = "";             // default XAMPP password (empty)
$dbname = "wedding_system"; // aapka database ka naam

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 echo "Connected successfully";  
?>