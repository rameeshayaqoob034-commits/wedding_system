<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wedding_system";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$sql = "SELECT * FROM bookings ORDER BY event_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Booking List</title>
<style>
body { font-family: Arial; background: #f4f6f8; padding: 20px;}
.container { max-width:1500px; margin:auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 8px 25px rgba(0,0,0,0.1);}
h2 { text-align:center; color:#333; margin-bottom:20px;}
.table-wrapper { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:14px; min-width:1300px;}
th, td { border:1px solid #ccc; padding:10px; text-align:center; vertical-align:middle;}
th { background:#007bff; color:white; font-weight:500; position:sticky; top:0; }
tr:nth-child(even){background-color:#f9f9f9;}
tr:hover { background-color:#e1f0ff; }
img.receipt { width:60px; height:auto; border-radius:4px; }
button { padding:6px 12px; border:none; border-radius:4px; cursor:pointer; }
.edit-btn { background:#28a745; color:white; }
.delete-btn { background:#dc3545; color:white; }
.view-btn { background:#007bff; color:white; }
.filter-container { margin-bottom:15px; display:flex; gap:10px; flex-wrap:wrap; }
.filter-container input, .filter-container select { padding:8px; border-radius:5px; border:1px solid #ccc; }
</style>
<script>
function filterTable() {
    let searchInput = document.getElementById("searchInput").value.toLowerCase();
    let clientFilter = document.getElementById("clientFilter").value.toLowerCase();
    let typeFilter = document.getElementById("typeFilter").value.toLowerCase();

    let table = document.getElementById("bookingTable");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let tds = tr[i].getElementsByTagName("td");
        if(tds.length == 0) continue;

        let clientName = tds[1].textContent.toLowerCase();
        let eventType = tds[5].textContent.toLowerCase();
        let combinedText = Array.from(tds).map(td => td.textContent.toLowerCase()).join(" ");

        if (combinedText.includes(searchInput) && 
            clientName.includes(clientFilter) &&
            eventType.includes(typeFilter)) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}
</script>
</head>
<body>
<div class="container">
<h2>Booking List</h2>

<div class="filter-container">
    <input type="text" id="searchInput" placeholder="Live Search..." onkeyup="filterTable()">
    <input type="text" id="clientFilter" placeholder="Filter by Client Name" onkeyup="filterTable()">
    <input type="text" id="typeFilter" placeholder="Filter by Event Type" onkeyup="filterTable()">
</div>

<div class="table-wrapper">
<table id="bookingTable">
<tr>
<th>Event ID</th>
<th>Client Name</th>
<th>Phone</th>
<th>Event Date</th>
<th>Event Time</th>
<th>Event Type</th>
<th>Hall</th>
<th>Guest Count</th>
<th>Hall Charges</th>
<th>Advance Payment</th>
<th>Decor</th>
<th>Heater</th>
<th>AC</th>
<th>Tax Amount</th>
<th>Breakage</th>
<th>Receipt</th>
<th>Payment Type</th>
<th>Payment Status</th>
<th>Event Status</th>
<th>Total Amount</th>
<th>Actions</th>
</tr>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $event_date_formatted = ($row['event_date'] && $row['event_date'] != '0000-00-00') ? date('d-m-Y', strtotime($row['event_date'])) : '-';
        echo "<tr>";
        echo "<td>".$row['event_id']."</td>";
        echo "<td>".$row['client_name']."</td>";
        echo "<td>".$row['phone']."</td>";
        echo "<td>{$event_date_formatted}</td>";
        echo "<td>".$row['event_time']."</td>";
        echo "<td>".$row['event_type']."</td>";
        echo "<td>".$row['hall']."</td>";
        echo "<td>".$row['guest_count']."</td>";
        echo "<td>".number_format($row['hall_charges'],2)."</td>";
        echo "<td>".number_format($row['advance_payment'],2)."</td>";
        echo "<td>".$row['Decor']."</td>";
        echo "<td>".$row['Heater']."</td>";
        echo "<td>".$row['Ac']."</td>";
        echo "<td>".number_format($row['Tax_Amount'],2)."</td>";
        echo "<td>".number_format($row['Breakage'],2)."</td>";

        // Receipt button
        if(!empty($row['Reciept_image'])){
            echo "<td><a href='uploads/".$row['Reciept_image']."' target='_blank'><button class='view-btn'>View Receipt</button></a></td>";
        } else {
            echo "<td>-</td>";
        }

        // Payment Type after Receipt
        echo "<td>".$row['Payment_Type']."</td>";

        echo "<td>".$row['payment_status']."</td>";
        echo "<td>".$row['event_status']."</td>";
        echo "<td>".number_format($row['total_amount'],2)."</td>";

        // Actions: Edit / Delete
        echo "<td>
            <a href='edit_booking.php?id=".$row['event_id']."'><button class='edit-btn'>Edit</button></a>
            <a href='delete_booking.php?id=".$row['event_id']."' onclick=\"return confirm('Are you sure?');\"><button class='delete-btn'>Delete</button></a>
        </td>";

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='21'>No bookings found</td></tr>";
}
?>
</table>
</div>
</div>
</body>
</html>
