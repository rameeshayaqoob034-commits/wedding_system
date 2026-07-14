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

// Total Bookings
$totalBookings = $conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'];

// Upcoming Events (Next 5)
$upcoming = $conn->query("SELECT * FROM bookings WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 5");

// Payment Status
$paid = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE payment_status='Paid'")->fetch_assoc()['count'];
$pending = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE payment_status='Pending'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body{font-family: Arial; margin:20px;}
        .card{border:1px solid #ccc; padding:20px; margin:10px; display:inline-block; width:200px; text-align:center; border-radius:8px;}
        table{border-collapse: collapse; width:90%; margin-top:20px;}
        th, td{border:1px solid #ccc; padding:8px; text-align:center;}
        th{background:#f4f4f4;}
    </style>
</head>
<body>

<h1>Dashboard</h1>

<div class="card">Total Bookings<br><b><?php echo $totalBookings; ?></b></div>
<div class="card" style="background:#d4edda;">Paid<br><b><?php echo $paid; ?></b></div>
<div class="card" style="background:#f8d7da;">Pending<br><b><?php echo $pending; ?></b></div>

<h2>Upcoming Events</h2>
<table>
<tr>
    <th>Client Name</th>
    <th>Phone</th>
    <th>Event Type</th>
    <th>Event Date</th>
    <th>Hall</th>
    <th>Guest Count</th>
    <th>Advance Payment</th>
    <th>Payment Status</th>
</tr>

<?php
while($row = $upcoming->fetch_assoc()){
?>
<tr>
    <td><?php echo $row['client_name']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['event_type']; ?></td>
    <td><?php echo $row['event_date']; ?></td>
    <td><?php echo $row['hall']; ?></td>
    <td><?php echo $row['guest_count']; ?></td>
    <td><?php echo $row['advance_payment']; ?></td>
    <td><?php echo $row['payment_status']; ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>