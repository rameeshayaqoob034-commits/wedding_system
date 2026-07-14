<?php

$conn = new mysqli("localhost","root","","wedding_system");

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

/* BOOKINGS */
$events = $conn->query("
SELECT *
FROM bookings
WHERE event_date='$date'
");

/* EXPENSES */
$expenses = $conn->query("
SELECT *
FROM expenses
WHERE event_date='$date'
");

/* TOTAL PROFIT */
$profit_query = $conn->query("
SELECT SUM(profit) as total_profit
FROM expenses
WHERE event_date='$date'
");

$profit = $profit_query->fetch_assoc()['total_profit'] ?? 0;

?>

<!DOCTYPE html>
<html>
<head>

<title>Daily Report</title>

<style>
body{
font-family:Arial;
background:#f4f6f9;
padding:20px;
}

h2{
text-align:center;
}

table{
width:100%;
border-collapse:collapse;
margin-bottom:30px;
}

th,td{
border:1px solid #ccc;
padding:6px;
text-align:center;
font-size:13px;
}

th{
background:#1f3a63;
color:white;
}

button{
padding:8px 15px;
margin:10px;
cursor:pointer;
}
</style>

</head>

<body>

<h2>Daily Event Report</h2>

<form method="GET">
<input type="date" name="date" value="<?php echo $date; ?>">
<button type="submit">Generate</button>
</form>

<a href="daily_report_pdf.php?date=<?php echo $date; ?>">
<button>Download PDF</button>
</a>

<button onclick="window.print()">Print</button>

<!-- ================= BOOKINGS TABLE ================= -->

<h3>Bookings / Events</h3>

<table>

<tr>

<?php
// SHOW ALL BOOKING COLUMNS AUTOMATICALLY
$columns = $conn->query("SHOW COLUMNS FROM bookings");
while($col = $columns->fetch_assoc()){
    echo "<th>".$col['Field']."</th>";
}
?>

</tr>

<?php

if($events->num_rows>0){

while($row=$events->fetch_assoc()){

echo "<tr>";

foreach($row as $value){
    echo "<td>".$value."</td>";
}

echo "</tr>";

}

}else{

echo "<tr><td colspan='100%'>No Events Found</td></tr>";

}

?>

</table>


<!-- ================= EXPENSE TABLE ================= -->

<h3>Event Expenses</h3>

<table>

<tr>

<?php
// SHOW ALL EXPENSE COLUMNS AUTOMATICALLY
$exp_columns = $conn->query("SHOW COLUMNS FROM expenses");
while($col = $exp_columns->fetch_assoc()){
    echo "<th>".$col['Field']."</th>";
}
?>

</tr>

<?php

$total_expense = 0;

if($expenses->num_rows>0){

while($row=$expenses->fetch_assoc()){

$total_expense += $row['total_expense'];

echo "<tr>";

foreach($row as $value){
    echo "<td>".$value."</td>";
}

echo "</tr>";

}

}else{

echo "<tr><td colspan='100%'>No Expense Data Found</td></tr>";

}

?>

</table>

<h3>Total Expenses: Rs <?php echo $total_expense; ?></h3>

<h3>Total Profit: Rs <?php echo $profit; ?></h3>

</body>
</html>