<?php
$conn = new mysqli("localhost","root","","wedding_system");

/* ================= GET DATA ================= */
if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM monthly_profit_report WHERE id=$id");
    $data = $result->fetch_assoc();
}

/* ================= UPDATE WITH RE-CALCULATION ================= */
if(isset($_POST['update'])){

    $id = $_POST['id'];
    $month = $_POST['month'];
    $year  = $_POST['year'];

    // 🔥 RE-CALCULATE EVENTS PROFIT FROM EXPENSES TABLE
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

    // 🔥 NEW TOTAL PROFIT
    $total_profit = $monthly_events_profit - $monthly_expenses;

    // Update Query
    $stmt = $conn->prepare("
        UPDATE monthly_profit_report SET
        month=?, year=?, electricity_cost=?, rent_of_land=?,
        staff_salaries=?, miscellaneous_cost=?,
        monthly_events_profit=?, monthly_expenses=?, total_profit=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "iiiddddddi",
        $month,
        $year,
        $electricity,
        $rent,
        $staff,
        $misc,
        $monthly_events_profit,
        $monthly_expenses,
        $total_profit,
        $id
    );

    $stmt->execute();

    header("Location: monthly_profit_report_list.php");
    exit();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
<div class="card p-4 shadow">

<h3 class="text-center text-primary">
Edit Monthly Profit Report
</h3>

<form method="post">

<input type="hidden" name="id" value="<?= $data['id']; ?>">

<input type="number" name="month"
value="<?= $data['month']; ?>"
class="form-control mb-2" required>

<input type="number" name="year"
value="<?= $data['year']; ?>"
class="form-control mb-2" required>

<input type="number" name="electricity_cost"
value="<?= $data['electricity_cost']; ?>"
class="form-control mb-2">

<input type="number" name="rent_of_land"
value="<?= $data['rent_of_land']; ?>"
class="form-control mb-2">

<input type="number" name="staff_salaries"
value="<?= $data['staff_salaries']; ?>"
class="form-control mb-2">

<input type="number" name="miscellaneous_cost"
value="<?= $data['miscellaneous_cost']; ?>"
class="form-control mb-3">

<button type="submit" name="update"
class="btn btn-success">
Update
</button>

</form>

</div>
</div>