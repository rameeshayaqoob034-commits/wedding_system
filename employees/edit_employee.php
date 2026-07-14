<?php

$conn = new mysqli("localhost","root","","wedding_system");

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

if(isset($_GET['id'])){
$id = $_GET['id'];
}else{
die("Employee ID not found");
}

$result = $conn->query("SELECT * FROM employees WHERE id='$id'");
$row = $result->fetch_assoc();

if(isset($_POST['update'])){

$name = $_POST['name'];
$salary = $_POST['salary'];
$advance = $_POST['advance'];
$attendance = $_POST['attendance'];
$id_card = $_POST['id_card'];
$phone = $_POST['phone'];

$agreement = $row['Agreement_letter'];

if(!empty($_FILES['agreement']['name'])){

$file_name = $_FILES['agreement']['name'];
$temp_name = $_FILES['agreement']['tmp_name'];

$folder = "uploads/".$file_name;

move_uploaded_file($temp_name,$folder);

$agreement = $file_name;

}

$sql = "UPDATE employees SET
Name='$name',
Salary='$salary',
Advance='$advance',
Attendance='$attendance',
Id_card='$id_card',
Phone='$phone',
Agreement_letter='$agreement'
WHERE id='$id'";

if($conn->query($sql)){
header("Location:list_employees.php?msg=updated");
exit;
}else{
echo "Update Failed";
}

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Employee</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:40px;
}

.container{
width:450px;
background:white;
padding:25px;
border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

h2{
margin-bottom:20px;
}

label{
font-weight:bold;
}

input{
width:100%;
padding:8px;
margin-top:5px;
margin-bottom:15px;
border:1px solid #ccc;
border-radius:4px;
}

button{
background:#2c3e50;
color:white;
padding:10px 15px;
border:none;
border-radius:4px;
cursor:pointer;
}

button:hover{
background:#34495e;
}

</style>

</head>

<body>

<div class="container">

<h2>Edit Employee</h2>

<form method="POST" enctype="multipart/form-data">

<label>Name</label>
<input type="text" name="name" value="<?php echo $row['Name']; ?>">

<label>Salary</label>
<input type="text" name="salary" value="<?php echo $row['Salary']; ?>">

<label>Advance</label>
<input type="text" name="advance" value="<?php echo $row['Advance']; ?>">

<label>Attendance</label>
<input type="text" name="attendance" value="<?php echo $row['Attendance']; ?>">

<label>ID Card</label>
<input type="text" name="id_card" value="<?php echo $row['Id_card']; ?>">

<label>Phone</label>
<input type="text" name="phone" value="<?php echo $row['Phone']; ?>">

<label>Upload Agreement Letter</label>
<input type="file" name="agreement">

<button name="update">Update Employee</button>

</form>

</div>

</body>

</html>