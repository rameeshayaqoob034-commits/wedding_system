<?php
$conn = new mysqli("localhost","root","","wedding_system");

if(isset($_POST['save_report'])){

    $stmt = $conn->prepare("
        INSERT INTO monthly_profit_report
        (month, year, electricity_cost, rent_of_land,
         staff_salaries, miscellaneous_cost,
         monthly_events_profit, monthly_expenses, total_profit)
        VALUES (?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "iiidddddd",
        $_POST['month'],
        $_POST['year'],
        $_POST['electricity'],
        $_POST['rent'],
        $_POST['staff'],
        $_POST['misc'],
        $_POST['monthly_events_profit'],
        $_POST['monthly_expenses'],
        $_POST['total_profit']
    );

    $stmt->execute();

    header("Location: monthly_profit_report_list.php");
    exit();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
<div class="card p-4 shadow">

<h3 class="text-center text-success mb-3">
Calculated Summary
</h3>

<table class="table table-bordered">

<tr><th>Month</th><td><?php echo $_GET['month']; ?></td></tr>
<tr><th>Year</th><td><?php echo $_GET['year']; ?></td></tr>
<tr><th>Monthly Events Profit</th><td><?php echo $_GET['monthly_events_profit']; ?></td></tr>
<tr><th>Monthly Expenses</th><td><?php echo $_GET['monthly_expenses']; ?></td></tr>
<tr><th>Total Profit</th><td><?php echo $_GET['total_profit']; ?></td></tr>

</table>

<form method="post">

<input type="hidden" name="month" value="<?php echo $_GET['month']; ?>">
<input type="hidden" name="year" value="<?php echo $_GET['year']; ?>">
<input type="hidden" name="electricity" value="<?php echo $_GET['electricity']; ?>">
<input type="hidden" name="rent" value="<?php echo $_GET['rent']; ?>">
<input type="hidden" name="staff" value="<?php echo $_GET['staff']; ?>">
<input type="hidden" name="misc" value="<?php echo $_GET['misc']; ?>">
<input type="hidden" name="monthly_events_profit" value="<?php echo $_GET['monthly_events_profit']; ?>">
<input type="hidden" name="monthly_expenses" value="<?php echo $_GET['monthly_expenses']; ?>">
<input type="hidden" name="total_profit" value="<?php echo $_GET['total_profit']; ?>">

<button type="submit" name="save_report" class="btn btn-success">
Save Report
</button>

</form>

</div>
</div>