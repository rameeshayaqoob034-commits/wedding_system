<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wedding_system";

// Database connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get events from bookings table
$sql_events = "SELECT event_id, client_name, event_date, event_time, total_amount FROM bookings ORDER BY event_date DESC";
$result_events = $conn->query($sql_events);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Expense</title>
<style>
body{font-family:Arial;padding:20px;background:#f7f7f7;}
form{background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);}
input[type=number], select{width:100%;padding:8px;margin:5px 0 15px;border:1px solid #ccc;border-radius:4px;}
input[type=submit]{background:#4CAF50;color:#fff;padding:10px 20px;border:none;border-radius:4px;cursor:pointer;}
input[type=submit]:hover{background:#45a049;}
label{font-weight:bold;}
h2{color:#333;}
</style>
</head>
<body>
<h2>Add Expense</h2>
<form action="add_expense_process.php" method="post">

  <label>Event</label>
  <select name="event_id" required>
    <option value="">--Select Event--</option>
    <?php while($row = $result_events->fetch_assoc()) { ?>
      <option value="<?php echo $row['event_id']; ?>">
        <?php echo $row['client_name']." | ".$row['event_date']." | ".$row['event_time']; ?>
      </option>
    <?php } ?>
  </select>

  <label>Meat Cost</label>
  <input type="number" name="meat_cost" value="0" step="0.01" required>

  <label>Vegetable Cost</label>
  <input type="number" name="vegetable_cost" value="0" step="0.01" required>

  <label>Dairy Cost</label>
  <input type="number" name="dairy_cost" value="0" step="0.01" required>

  <label>Other Grocery</label>
  <input type="number" name="other_grocery" value="0" step="0.01" required>

  <label>Generator Cost</label>
  <input type="number" name="generator_cost" value="0" step="0.01" required>

  <label>Waiter Wages</label>
  <input type="number" name="waiter_wages" value="0" step="0.01" required>

  <label>Cook Wages</label>
  <input type="number" name="cook_wages" value="0" step="0.01" required>

  <label>Miscellaneous Cost</label>
  <input type="number" name="miscellaneous_cost" value="0" step="0.01" required>



  <input type="submit" value="Add Expense">
</form>
</body>
</html>