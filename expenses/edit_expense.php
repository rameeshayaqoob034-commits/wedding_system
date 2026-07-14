<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wedding_system";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) { die("Connection failed: ".$conn->connect_error); }

// Get event_id from query string
if(!isset($_GET['event_id'])) { die("No Event ID provided."); }
$event_id = $_GET['event_id'];

// Fetch existing expense
$sql = "SELECT * FROM expenses WHERE event_id='$event_id'";
$result = $conn->query($sql);

if($result->num_rows == 0){ die("Expense not found."); }
$expense = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Expense</title>
<style>
body{font-family:Arial;padding:20px;background:#f7f7f7;}
form{background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);}
input[type=number]{width:100%;padding:8px;margin:5px 0 15px;border:1px solid #ccc;border-radius:4px;}
input[type=submit]{background:#2196F3;color:#fff;padding:10px 20px;border:none;border-radius:4px;cursor:pointer;}
input[type=submit]:hover{background:#0b7dda;}
label{font-weight:bold;}
h2{color:#333;}
</style>
</head>
<body>
<h2>Edit Expense</h2>
<form action="edit_expense_process.php" method="post">
<input type="hidden" name="event_id" value="<?php echo $expense['event_id']; ?>">

<label>Meat Cost</label>
<input type="number" name="meat_cost" value="<?php echo $expense['meat_cost']; ?>" step="0.01" required>

<label>Vegetable Cost</label>
<input type="number" name="vegetable_cost" value="<?php echo $expense['vegetable_cost']; ?>" step="0.01" required>

<label>Dairy Cost</label>
<input type="number" name="dairy_cost" value="<?php echo $expense['dairy_cost']; ?>" step="0.01" required>

<label>Other Grocery</label>
<input type="number" name="other_grocery" value="<?php echo $expense['other_grocery']; ?>" step="0.01" required>

<label>Generator Cost</label>
<input type="number" name="generator_cost" value="<?php echo $expense['generator_cost']; ?>" step="0.01" required>

<label>Waiter Wages</label>
<input type="number" name="waiter_wages" value="<?php echo $expense['waiter_wages']; ?>" step="0.01" required>

<label>Cook Wages</label>
<input type="number" name="cook_wages" value="<?php echo $expense['cook_wages']; ?>" step="0.01" required>

<label>Miscellaneous Cost</label>
<input type="number" name="miscellaneous_cost" value="<?php echo $expense['miscellaneous_cost']; ?>" step="0.01" required>

<input type="submit" value="Update Expense">
</form>
</body>
</html>