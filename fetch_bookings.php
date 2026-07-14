<?php
$conn = new mysqli("localhost", "root", "", "wedding_system");
if($conn->connect_error){ die("Connection failed: ".$conn->connect_error); }

// Filters
$client = $_GET['client_name'] ?? '';
$event_type = $_GET['event_type'] ?? '';
$ac = $_GET['ac'] ?? '';
$payment_status = $_GET['payment_status'] ?? '';
$event_date = $_GET['event_date'] ?? '';

// Pagination & Sorting
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rowsPerPage = isset($_GET['rows_per_page']) ? intval($_GET['rows_per_page']) : 10;
$offset = ($page - 1) * $rowsPerPage;

$sort_column = $_GET['sort_column'] ?? 'event_date';
$sort_order = $_GET['sort_order'] ?? 'DESC';
$allowed_columns = ['id','client_name','phone','event_type','event_date','hall','guest_count','hall_charges','advance_payment','payment_status','Decor','Heater','Ac','Tax_Amount','Breakage','Payment_Type'];
if(!in_array($sort_column, $allowed_columns)){ $sort_column = 'event_date'; }
$sort_order = ($sort_order === 'ASC') ? 'ASC' : 'DESC';

// Query
$sql = "SELECT * FROM bookings WHERE 1=1";
if($client != '') { $sql .= " AND client_name LIKE '%".$conn->real_escape_string($client)."%'"; }
if($event_type != '') { $sql .= " AND event_type LIKE '%".$conn->real_escape_string($event_type)."%'"; }
if($ac != '') { $sql .= " AND Ac LIKE '%".$conn->real_escape_string($ac)."%'"; }
if($payment_status != '') { $sql .= " AND payment_status LIKE '%".$conn->real_escape_string($payment_status)."%'"; }
if($event_date != '') { $sql .= " AND event_date = '".$conn->real_escape_string($event_date)."'"; }

$total_result = $conn->query($sql);
$total_rows = $total_result->num_rows;
$total_pages = ceil($total_rows / $rowsPerPage);

$sql .= " ORDER BY $sort_column $sort_order LIMIT $offset,$rowsPerPage";
$result = $conn->query($sql);

// Table
echo '<table>';
echo '<tr>
<th class="sortable" data-column="id">ID</th>
<th class="sortable" data-column="client_name">Client</th>
<th class="sortable" data-column="phone">Phone</th>
<th class="sortable" data-column="event_type">Event Type</th>
<th class="sortable" data-column="event_date">Event Date</th>
<th class="sortable" data-column="hall">Hall</th>
<th class="sortable" data-column="guest_count">Guest Count</th>
<th class="sortable" data-column="hall_charges">Hall Charges</th>
<th class="sortable" data-column="advance_payment">Advance Payment</th>
<th class="sortable" data-column="payment_status">Payment Status</th>
<th class="sortable" data-column="Decor">Decor</th>
<th class="sortable" data-column="Heater">Heater</th>
<th class="sortable" data-column="Ac">AC</th>
<th class="sortable" data-column="Tax_Amount">Tax Amount</th>
<th class="sortable" data-column="Breakage">Breakage</th>
<th class="sortable" data-column="Payment_Type">Payment Type</th>
<th>Reciept</th>
<th>Actions</th>
</tr>';

while($row = $result->fetch_assoc()){
    echo '<tr>
    <td>'.$row['id'].'</td>
    <td>'.htmlspecialchars($row['client_name']).'</td>
    <td>'.htmlspecialchars($row['phone']).'</td>
    <td>'.htmlspecialchars($row['event_type']).'</td>
    <td>'.$row['event_date'].'</td>
    <td>'.htmlspecialchars($row['hall']).'</td>
    <td>'.$row['guest_count'].'</td>
    <td>'.$row['hall_charges'].'</td>
    <td>'.$row['advance_payment'].'</td>
    <td>'.htmlspecialchars($row['payment_status']).'</td>
    <td>'.($row['Decor'] !== '' ? htmlspecialchars($row['Decor']) : '-').'</td>
    <td>'.($row['Heater'] !== '' ? htmlspecialchars($row['Heater']) : '-').'</td>
    <td>'.($row['Ac'] !== '' ? htmlspecialchars($row['Ac']) : '-').'</td>
    <td>'.($row['Tax_Amount'] !== '' ? htmlspecialchars($row['Tax_Amount']) : '-').'</td>
    <td>'.($row['Breakage'] !== '' ? htmlspecialchars($row['Breakage']) : '-').'</td>
    <td>'.($row['Payment_Type'] !== '' ? htmlspecialchars($row['Payment_Type']) : '-').'</td>
    <td>';
    $rec = $row['Reciept_image'] ?? '';
    $filePath = __DIR__.'/uploads/'.$rec;
    if(!empty($rec) && file_exists($filePath)){
        echo '<a class="view" href="uploads/'.urlencode($rec).'" target="_blank">View</a>';
    } else { echo '-'; }
    echo '</td>
    <td>
        <a class="edit" href="edit_booking.php?id='.$row['id'].'">Edit</a>
        <a class="delete" href="delete_booking.php?id='.$row['id'].'" onclick="return confirm(\'Are you sure?\');">Delete</a>
    </td>
    </tr>';
}
echo '</table>';

// Pagination
echo '<div class="pagination">';
for($i=1;$i<=$total_pages;$i++){
    $active = ($i==$page) ? 'active' : '';
    echo '<button class="'.$active.'" data-page="'.$i.'">'.$i.'</button>';
}
echo '</div>';
?>