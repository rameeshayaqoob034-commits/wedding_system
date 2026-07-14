<?php
$conn = new mysqli("localhost", "root", "", "wedding_system");
if($conn->connect_error){ die("Connection failed: ".$conn->connect_error); }

$format = $_GET['format'] ?? 'csv';

// Get filters from live search inputs
$client = $_GET['client_name'] ?? '';
$event_type = $_GET['event_type'] ?? '';
$ac = $_GET['ac'] ?? '';
$payment_status = $_GET['payment_status'] ?? '';
$event_date = $_GET['event_date'] ?? '';

// Build query with filters
$sql = "SELECT * FROM bookings WHERE 1=1";
if($client != '') { $sql .= " AND client_name LIKE '%".$conn->real_escape_string($client)."%'"; }
if($event_type != '') { $sql .= " AND event_type LIKE '%".$conn->real_escape_string($event_type)."%'"; }
if($ac != '') { $sql .= " AND Ac LIKE '%".$conn->real_escape_string($ac)."%'"; }
if($payment_status != '') { $sql .= " AND payment_status LIKE '%".$conn->real_escape_string($payment_status)."%'"; }
if($event_date != '') { $sql .= " AND event_date = '".$conn->real_escape_string($event_date)."'"; }

$result = $conn->query($sql);

$filename = "bookings_export_".date('Ymd_His');

// --- CSV Export ---
if($format == 'csv'){
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID','Client','Phone','Event Type','Event Date','Hall','Guest Count','Hall Charges','Advance Payment','Payment Status','Decor','Heater','AC','Tax Amount','Breakage','Payment Type','Reciept'));
    while($row = $result->fetch_assoc()){
        fputcsv($output, array(
            $row['id'],
            $row['client_name'],
            $row['phone'],
            $row['event_type'],
            $row['event_date'],
            $row['hall'],
            $row['guest_count'],
            $row['hall_charges'],
            $row['advance_payment'],
            $row['payment_status'],
            $row['Decor'],
            $row['Heater'],
            $row['Ac'],
            $row['Tax_Amount'],
            $row['Breakage'],
            $row['Payment_Type'],
            $row['Reciept_image']
        ));
    }
    fclose($output);
}

// --- Excel Export ---
if($format == 'excel'){
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=".$filename.".xls");
    echo "<table border='1'><tr>
    <th>ID</th><th>Client</th><th>Phone</th><th>Event Type</th><th>Event Date</th><th>Hall</th>
    <th>Guest Count</th><th>Hall Charges</th><th>Advance Payment</th><th>Payment Status</th>
    <th>Decor</th><th>Heater</th><th>AC</th><th>Tax Amount</th><th>Breakage</th><th>Payment Type</th><th>Reciept</th>
    </tr>";
    while($row = $result->fetch_assoc()){
        echo "<tr>
        <td>".$row['id']."</td>
        <td>".$row['client_name']."</td>
        <td>".$row['phone']."</td>
        <td>".$row['event_type']."</td>
        <td>".$row['event_date']."</td>
        <td>".$row['hall']."</td>
        <td>".$row['guest_count']."</td>
        <td>".$row['hall_charges']."</td>
        <td>".$row['advance_payment']."</td>
        <td>".$row['payment_status']."</td>
        <td>".$row['Decor']."</td>
        <td>".$row['Heater']."</td>
        <td>".$row['Ac']."</td>
        <td>".$row['Tax_Amount']."</td>
        <td>".$row['Breakage']."</td>
        <td>".$row['Payment_Type']."</td>
        <td>".$row['Reciept_image']."</td>
        </tr>";
    }
    echo "</table>";
}
?>