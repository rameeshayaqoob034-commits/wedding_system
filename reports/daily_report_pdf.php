<?php

require '../vendor/autoload.php';

use Dompdf\Dompdf;

$conn = new mysqli("localhost","root","","wedding_system");

$date = $_GET['date'];

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

$html = "<h2 style='text-align:center;'>Daily Event Report ($date)</h2>";

/* BOOKINGS TABLE */

$html .= "<h3>Bookings</h3>";

$html .= "<table border='1' width='100%' cellspacing='0' cellpadding='5'>

<tr>

<th>Event ID</th>
<th>Client Name</th>
<th>Phone</th>
<th>Event Date</th>
<th>Event Time</th>
<th>Event Type</th>
<th>Hall</th>
<th>Guests</th>
<th>Hall Charges</th>
<th>Advance</th>
<th>Total Amount</th>

</tr>";

while($row=$events->fetch_assoc()){

$html .= "<tr>

<td>".$row['event_id']."</td>
<td>".$row['client_name']."</td>
<td>".$row['phone']."</td>
<td>".$row['event_date']."</td>
<td>".$row['event_time']."</td>
<td>".$row['event_type']."</td>
<td>".$row['hall']."</td>
<td>".$row['guest_count']."</td>
<td>".$row['hall_charges']."</td>
<td>".$row['advance_payment']."</td>
<td>".$row['total_amount']."</td>

</tr>";

}

$html .= "</table>";

/* EXPENSE TABLE */

$html .= "<h3>Expenses</h3>";

$html .= "<table border='1' width='100%' cellspacing='0' cellpadding='5'>

<tr>

<th>Event ID</th>
<th>Meat</th>
<th>Vegetable</th>
<th>Dairy</th>
<th>Other Grocery</th>
<th>Generator</th>
<th>Waiter</th>
<th>Cook</th>
<th>Misc</th>
<th>Total Expense</th>
<th>Profit</th>

</tr>";

while($row=$expenses->fetch_assoc()){

$html .= "<tr>

<td>".$row['event_id']."</td>
<td>".$row['meat_cost']."</td>
<td>".$row['vegetable_cost']."</td>
<td>".$row['dairy_cost']."</td>
<td>".$row['other_grocery']."</td>
<td>".$row['generator_cost']."</td>
<td>".$row['waiter_wages']."</td>
<td>".$row['cook_wages']."</td>
<td>".$row['miscellaneous_cost']."</td>
<td>".$row['total_expense']."</td>
<td>".$row['profit']."</td>

</tr>";

}

$html .= "</table>";

$dompdf = new Dompdf();

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream("daily_report.pdf");

?>