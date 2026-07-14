<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "wedding_system";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['submit'])) {

    $Name = $_POST['Name'];
    $Salary = $_POST['Salary'];
    $Advance = $_POST['Advance'];
    $Attendance = $_POST['Attendance'];
    $Id_card = $_POST['Id_card'];
    $Phone = $_POST['Phone'];

    // Agreement Letter Upload
    $Agreement_letter_db = '';
    if(isset($_FILES['Agreement_letter']) && $_FILES['Agreement_letter']['name'] != '') {

        $fileError = $_FILES['Agreement_letter']['error'];
        $fileTmp = $_FILES['Agreement_letter']['tmp_name'];
        $fileName = basename($_FILES['Agreement_letter']['name']);
        $allowedExt = ['jpg','jpeg','png','pdf','doc','docx'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if($fileError === 0){
            if(in_array($ext, $allowedExt)){
                $uploadPath = __DIR__ . '/../uploads/employees/' . $fileName;

                if(move_uploaded_file($fileTmp, $uploadPath)){
                    $Agreement_letter_db = 'uploads/employees/' . $fileName;
                }
            }
        }
    }

    // Employee Photo Upload
    $Photo_db = '';
    if(isset($_FILES['Photo']) && $_FILES['Photo']['name'] != '') {

        $photoError = $_FILES['Photo']['error'];
        $photoTmp = $_FILES['Photo']['tmp_name'];
        $photoName = basename($_FILES['Photo']['name']);
        $allowedPhotoExt = ['jpg','jpeg','png'];
        $photoExt = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));

        if($photoError === 0){
            if(in_array($photoExt, $allowedPhotoExt)){
                $photoUploadPath = __DIR__ . '/../uploads/employees/' . $photoName;

                if(move_uploaded_file($photoTmp, $photoUploadPath)){
                    $Photo_db = 'uploads/employees/' . $photoName;
                }
            } else {
                echo "<p style='color:red;'>Invalid photo type! Only JPG, JPEG, PNG allowed.</p>";
            }
        }
    }

    $sql = "INSERT INTO employees 
    (Name, Salary, Advance, Attendance, Id_card, Phone, Agreement_letter, Photo)
    VALUES 
    ('$Name', '$Salary', '$Advance', '$Attendance', '$Id_card', '$Phone', '$Agreement_letter_db', '$Photo_db')";

    if($conn->query($sql) === TRUE){
        echo "<p style='color:green;'>Employee added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: ".$conn->error."</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Employee</title>

<style>
body { font-family: Arial; background-color: #f4f7f9; padding: 30px; }

h2 { color: #001f5b; margin-bottom: 20px; }

form {
background:white;
padding:20px;
border-radius:8px;
box-shadow:0 0 15px rgba(0,0,0,0.1);
max-width:600px;
}

label { display:block; margin-top:15px; font-weight:bold; }

input[type="text"],
input[type="number"],
textarea,
input[type="file"] {
width:100%;
padding:8px;
margin-top:5px;
border-radius:4px;
border:1px solid #ccc;
box-sizing:border-box;
}

textarea { resize: vertical; }

input[type="submit"] {
background-color:#001f5b;
color:white;
padding:10px 20px;
margin-top:20px;
border:none;
border-radius:5px;
cursor:pointer;
}

input[type="submit"]:hover {
background-color:#003366;
}

p { margin-top:10px; }
</style>

</head>

<body>

<h2>Add Employee</h2>

<form action="" method="post" enctype="multipart/form-data">

<label>Name:</label>
<input type="text" name="Name" required>

<label>Salary:</label>
<input type="number" step="0.01" name="Salary" required>

<label>Advance:</label>
<input type="number" step="0.01" name="Advance">

<label>Attendance:</label>
<textarea name="Attendance"></textarea>

<label>ID Card Number:</label>
<input type="text" name="Id_card">

<label>Phone Number:</label>
<input type="text" name="Phone">

<label>Employee Photo (JPG, PNG):</label>
<input type="file" name="Photo">

<label>Agreement Letter (JPG, PNG, PDF, DOC, DOCX):</label>
<input type="file" name="Agreement_letter">

<input type="submit" name="submit" value="Add Employee">

</form>

</body>
</html>