<?php
include 'db.php';

if(!isset($_GET['employee_id'])){
    echo json_encode([]);
    exit;
}

$emp_id = $conn->real_escape_string($_GET['employee_id']);

// Fetch all attendance records for this employee
$sql = "SELECT Date, Status, Remarks FROM employee_attendance WHERE Id_card='$emp_id' ORDER BY Date ASC";
$result = $conn->query($sql);

$attendance = [];
while($row = $result->fetch_assoc()){
    $attendance[] = $row;
}

echo json_encode($attendance);
$conn->close();