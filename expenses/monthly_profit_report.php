<?php
ob_start();
$conn = new mysqli("localhost","root","","wedding_system");

if($conn->connect_error){
    die("Connection Failed");
}

if(isset($_POST['calculate'])){

    $month = $_POST['month'];
    $year  = $_POST['year'];

    // Get Monthly Events Profit (SUM of existing profit column)
    $result = $conn->query("
        SELECT IFNULL(SUM(profit),0) AS monthly_events_profit
        FROM expenses
        WHERE MONTH(event_date)='$month'
        AND YEAR(event_date)='$year'
    ");

    $row = $result->fetch_assoc();
    $monthly_events_profit = $row['monthly_events_profit'];

    // Manual Costs
    $electricity = floatval($_POST['electricity_cost']);
    $rent        = floatval($_POST['rent_of_land']);
    $staff       = floatval($_POST['staff_salaries']);
    $misc        = floatval($_POST['miscellaneous_cost']);

    $monthly_expenses = $electricity + $rent + $staff + $misc;

    $total_profit = $monthly_events_profit - $monthly_expenses;

    // SAFE REDIRECT (No newline issue)
    $query = http_build_query([
        "month" => $month,
        "year" => $year,
        "electricity" => $electricity,
        "rent" => $rent,
        "staff" => $staff,
        "misc" => $misc,
        "monthly_events_profit" => $monthly_events_profit,
        "monthly_expenses" => $monthly_expenses,
        "total_profit" => $total_profit
    ]);

    header("Location: monthly_profit_preview.php?$query");
    exit();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
<div class="card p-4 shadow">

<h3 class="text-center text-primary mb-3">
Monthly Profit Report
</h3>

<form method="post">

<select name="month" class="form-control mb-2" required>
<option value="">Select Month</option>
<?php for($m=1;$m<=12;$m++){ ?>
<option value="<?php echo $m; ?>"><?php echo $m; ?></option>
<?php } ?>
</select>

<select name="year" class="form-control mb-2" required>
<option value="">Select Year</option>
<?php for($y=2025;$y<=2065;$y++){ ?>
<option value="<?php echo $y; ?>"><?php echo $y; ?></option>
<?php } ?>
</select>

<input type="number" name="electricity_cost" class="form-control mb-2" placeholder="Electricity Cost" required>
<input type="number" name="rent_of_land" class="form-control mb-2" placeholder="Rent Cost" required>
<input type="number" name="staff_salaries" class="form-control mb-2" placeholder="Staff Salaries" required>
<input type="number" name="miscellaneous_cost" class="form-control mb-3" placeholder="Misc Cost" required>

<button type="submit" name="calculate" class="btn btn-primary">
Calculate
</button>

</form>

</div>
</div>

<?php ob_end_flush(); ?>