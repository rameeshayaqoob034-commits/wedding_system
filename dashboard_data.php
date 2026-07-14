<?php

$conn = new mysqli("localhost","root","","wedding_system");

$data=[];

/* TOTAL BOOKINGS */

$q=$conn->query("SELECT COUNT(*) as total FROM bookings");
$data['total_bookings']=$q->fetch_assoc()['total'];


/* UPCOMING EVENTS */

$q=$conn->query("SELECT COUNT(*) as total FROM bookings WHERE event_date >= CURDATE()");
$data['upcoming_events']=$q->fetch_assoc()['total'];


/* TOTAL REVENUE */

$q=$conn->query("SELECT SUM(hall_charges) as total FROM bookings");
$data['total_revenue']=$q->fetch_assoc()['total'] ?? 0;


/* PENDING PAYMENTS */

$q=$conn->query("SELECT COUNT(*) as total FROM bookings WHERE payment_status='Pending'");
$data['pending_payments']=$q->fetch_assoc()['total'];


/* PAYMENT STATUS */

$q=$conn->query("SELECT payment_status,COUNT(*) as total FROM bookings GROUP BY payment_status");

$paid=0;
$partial=0;
$pending=0;

while($row=$q->fetch_assoc()){

if($row['payment_status']=="Paid") $paid=$row['total'];
if($row['payment_status']=="Partial") $partial=$row['total'];
if($row['payment_status']=="Pending") $pending=$row['total'];

}

$data['paid']=$paid;
$data['partial']=$partial;
$data['pending']=$pending;


/* MONTHLY BOOKINGS */

$q=$conn->query("
SELECT MONTH(event_date) as month,
COUNT(*) as total
FROM bookings
GROUP BY MONTH(event_date)
");

$labels=[];
$values=[];

while($row=$q->fetch_assoc()){

$labels[]="Month ".$row['month'];
$values[]=$row['total'];

}

$data['month_labels']=$labels;
$data['month_data']=$values;


/* UPCOMING EVENTS TABLE */

$table="<table>
<tr>
<th>Client</th>
<th>Event</th>
<th>Date</th>
<th>Guests</th>
<th>Status</th>
</tr>";

$q=$conn->query("
SELECT client_name,event_type,event_date,guest_count,payment_status
FROM bookings
WHERE event_date >= CURDATE()
ORDER BY event_date ASC
LIMIT 5
");

while($r=$q->fetch_assoc()){

$table.="<tr>
<td>{$r['client_name']}</td>
<td>{$r['event_type']}</td>
<td>{$r['event_date']}</td>
<td>{$r['guest_count']}</td>
<td>{$r['payment_status']}</td>
</tr>";

}

$table.="</table>";

$data['upcoming_table']=$table;

echo json_encode($data);

?>