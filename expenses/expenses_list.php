<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wedding_system";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) { die("Connection failed: ".$conn->connect_error); }

$sql = "SELECT * FROM expenses ORDER BY event_id DESC";
$result = $conn->query($sql);

$total_profit = 0; // NEW: total profit variable
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Expenses List & Profit</title>
<style>
body{
    font-family: 'Arial', sans-serif;
    background:#f0f2f5;
    margin:0;
    padding:20px;
    text-align:center;
}
h2{
    color:#333;
    font-weight:bold;
    margin-bottom:20px;
    font-size:28px;
}
table{
    border-collapse: collapse;
    width:95%;
    margin: 0 auto;
    background:#fff;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    border-radius:8px;
    overflow:hidden;
}
th, td{
    padding:12px 15px;
    text-align:center;
    border:1px solid #999;
}
th{
    background-color:#001f3f;
    color:#fff;
    font-size:16px;
}
tr:nth-child(even){
    background:#f9f9f9;
}
tr:hover{
    background:#e6f7ff;
}
a.button{
    padding:6px 12px;
    border-radius:5px;
    color:#fff;
    text-decoration:none;
    display:inline-block;
    margin:2px 2px;
    font-size:14px;
}
a.edit{background:#2196F3;}
a.delete{background:#f44336;}
a.edit:hover{background:#0b7dda;}
a.delete:hover{background:#da190b;}

.totalRow{
    font-weight:bold;
    background:#ffe9a7;
}
</style>
</head>
<body>

<h2>Expenses List & Profit</h2>

<table>

<tr>
<th>Event ID</th>
<th>Client Name</th>
<th>Event Date</th>
<th>Event Time</th>
<th>Meat</th>
<th>Vegetable</th>
<th>Dairy</th>
<th>Other Grocery</th>
<th>Generator</th>
<th>Waiter</th>
<th>Cook</th>
<th>Misc</th>
<th>Total Expense</th>
<th>Total Amount</th>
<th>Profit</th>
<th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()) { 

$total_profit += $row['profit']; // NEW: add profit

?>

<tr>
<td><?php echo $row['event_id']; ?></td>
<td><?php echo $row['client_name']; ?></td>
<td><?php echo $row['event_date']; ?></td>
<td><?php echo $row['event_time']; ?></td>
<td><?php echo $row['meat_cost']; ?></td>
<td><?php echo $row['vegetable_cost']; ?></td>
<td><?php echo $row['dairy_cost']; ?></td>
<td><?php echo $row['other_grocery']; ?></td>
<td><?php echo $row['generator_cost']; ?></td>
<td><?php echo $row['waiter_wages']; ?></td>
<td><?php echo $row['cook_wages']; ?></td>
<td><?php echo $row['miscellaneous_cost']; ?></td>
<td><?php echo $row['total_expense']; ?></td>
<td><?php echo $row['total_amount']; ?></td>
<td><?php echo $row['profit']; ?></td>
<td>
<a class="button edit" href="edit_expense.php?event_id=<?php echo $row['event_id']; ?>">Edit</a>
<a class="button delete" href="delete_expense.php?event_id=<?php echo $row['event_id']; ?>" onclick="return confirm('Are you sure to delete this expense?');">Delete</a>
</td>
</tr>

<?php } ?>

<!-- TOTAL PROFIT ROW -->
<tr class="totalRow">
<td colspan="14">TOTAL PROFIT</td>
<td><?php echo $total_profit; ?></td>
<td></td>
</tr>

</table>

</body>
</html>