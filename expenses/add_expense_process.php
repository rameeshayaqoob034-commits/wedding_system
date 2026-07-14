<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wedding_system";

// Database connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}

// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Direct access not allowed.");
}

// POST variables
$event_id = $_POST['event_id'];
$meat_cost = $_POST['meat_cost'];
$vegetable_cost = $_POST['vegetable_cost'];
$dairy_cost = $_POST['dairy_cost'];
$other_grocery = $_POST['other_grocery'];
$generator_cost = $_POST['generator_cost'];
$waiter_wages = $_POST['waiter_wages'];
$cook_wages = $_POST['cook_wages'];
$miscellaneous_cost = $_POST['miscellaneous_cost'];

// Get event details from bookings table
$sql_event = "SELECT client_name, event_date, event_time, total_amount FROM bookings WHERE event_id=$event_id";
$result_event = $conn->query($sql_event);

if($result_event->num_rows>0){
    $event = $result_event->fetch_assoc();
    $client_name = $event['client_name'];
    $event_date_val = $event['event_date'];
    $event_time_val = $event['event_time'];
    $total_amount = $event['total_amount'];
} else {
    die("Event not found!");
}

// Calculate total expense & profit
$total_expense = $meat_cost + $vegetable_cost + $dairy_cost + $other_grocery + $generator_cost + $waiter_wages + $cook_wages + $miscellaneous_cost;
$profit = $total_amount - $total_expense;

// Insert into expenses table
$insert_sql = "INSERT INTO expenses 
(event_id, client_name, event_date, event_time, meat_cost, vegetable_cost, dairy_cost, other_grocery, generator_cost, waiter_wages, cook_wages, miscellaneous_cost, total_expense, total_amount, profit)
VALUES
('$event_id', '$client_name', '$event_date_val', '$event_time_val', '$meat_cost', '$vegetable_cost', '$dairy_cost', '$other_grocery', '$generator_cost', '$waiter_wages', '$cook_wages', '$miscellaneous_cost', '$total_expense', '$total_amount', '$profit')";

if($conn->query($insert_sql) === TRUE){
    echo "<script>alert('Expense Added Successfully'); window.location='expenses_list.php';</script>";
} else {
    echo "Error: ".$conn->error;
}

$conn->close();
?>