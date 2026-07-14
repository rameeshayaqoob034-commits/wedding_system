<?php
$conn = new mysqli("localhost","root","","wedding_system");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.card-box{
    border-radius:15px;
}
.table thead th{
    background-color:#002b5c !important;
    color:white !important;
}
.btn-edit{
    background-color:#0d6efd;
    color:white;
}
.btn-delete{
    background-color:#dc3545;
    color:white;
}
</style>

<div class="container mt-4">
<div class="card shadow card-box p-4">

<h3 class="text-center text-primary mb-4">
Monthly Profit Report List
</h3>

<!-- PRINT BUTTON -->
<div class="text-end mb-3">
<button onclick="printReport()" class="btn btn-success">
Print Report
</button>
</div>

<div id="reportTable">

<div class="table-responsive">
<table class="table table-bordered table-hover text-center align-middle">

<thead>
<tr>
<th>Month</th>
<th>Year</th>
<th>Electricity</th>
<th>Rent</th>
<th>Staff</th>
<th>Misc</th>
<th>Events Profit</th>
<th>Expenses</th>
<th>Total Profit</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php
$result = $conn->query("SELECT * FROM monthly_profit_report ORDER BY year DESC, month DESC");

while($row = $result->fetch_assoc()){
?>

<tr>
<td><?= $row['month']; ?></td>
<td><?= $row['year']; ?></td>
<td><?= $row['electricity_cost']; ?></td>
<td><?= $row['rent_of_land']; ?></td>
<td><?= $row['staff_salaries']; ?></td>
<td><?= $row['miscellaneous_cost']; ?></td>
<td><?= $row['monthly_events_profit']; ?></td>
<td><?= $row['monthly_expenses']; ?></td>
<td><b><?= $row['total_profit']; ?></b></td>

<td>
<a href="edit_monthly_profit.php?id=<?= $row['id']; ?>"
class="btn btn-sm btn-edit">Edit</a>

<a href="delete_monthly_profit.php?id=<?= $row['id']; ?>"
class="btn btn-sm btn-delete"
onclick="return confirm('Are you sure?');">
Delete
</a>
</td>

</tr>

<?php } ?>

</tbody>
</table>
</div>

</div>

</div>
</div>

<script>

function printReport(){

var printContents =
document.getElementById("reportTable").innerHTML;

var originalContents = document.body.innerHTML;

document.body.innerHTML = printContents;

window.print();

document.body.innerHTML = originalContents;

}

</script>