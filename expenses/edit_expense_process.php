<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wedding_system";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) { die("Connection failed: ".$conn->connect_error); }

if($_SERVER['REQUEST_METHOD'] !== 'POST'){ die("Direct access not allowed."); }

$event_id = isset($_POST['event_id']) ? $_POST['event_id'] : 0;
$meat_cost = isset($_POST['meat_cost']) ? $_POST['meat_cost'] : 0;
$vegetable_cost = isset($_POST['vegetable_cost']) ? $_POST['vegetable_cost'] : 0;
$dairy_cost = isset($_POST['dairy_cost']) ? $_POST['dairy_cost'] : 0;
$other_grocery = isset($_POST['other_grocery']) ? $_POST['other_grocery'] : 0;
$generator_cost = isset($_POST['generator_cost']) ? $_POST['generator_cost'] : 0;
$waiter_wages = isset($_POST['waiter_wages']) ? $_POST['waiter_wages'] : 0;
$cook_wages = isset($_POST['cook_wages']) ? $_POST['cook_wages'] : 0;
$miscellaneous_cost = isset($_POST['miscellaneous_cost']) ? $_POST['miscellaneous_cost'] : 0;

// Fetch total_amount from bookings
$sql_event = "SELECT total_amount, client_name, event_date, event_time FROM bookings WHERE event_id='$event_id'";
$result_event = $conn->query($sql_event);
if($result_event->num_rows==0){ die("Event not found."); }
$event = $result_event->fetch_assoc();
$total_amount = $event['total_amount'];
$client_name = $event['client_name'];
$event_date_val = $event['event_date'];
$event_time_val = $event['event_time'];

// Recalculate total_expense and profit
$total_expense = $meat_cost + $vegetable_cost + $dairy_cost + $other_grocery + $generator_cost + $waiter_wages + $cook_wages + $miscellaneous_cost;
$profit = $total_amount - $total_expense;

// Update expenses
$update_sql = "UPDATE expenses SET 
client_name='$client_name',
event_date='$event_date_val',
event_time='$event_time_val',
meat_cost='$meat_cost',
vegetable_cost='$vegetable_cost',
dairy_cost='$dairy_cost',
other_grocery='$other_grocery',
generator_cost='$generator_cost',
waiter_wages='$waiter_wages',
cook_wages='$cook_wages',
miscellaneous_cost='$miscellaneous_cost',
total_expense='$total_expense',
total_amount='$total_amount',
profit='$profit'
WHERE event_id='$event_id'";

if($conn->query($update_sql)===TRUE){
    echo "<script>alert('Expense Updated Successfully'); window.location='expenses_list.php';</script>";
} else { echo "Error: ".$conn->error; }

$conn->close();
?>