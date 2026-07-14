<?php
$conn = new mysqli("localhost","root","","wedding_system");

if(isset($_GET['id'])){
    $id = intval($_GET['id']);

    $conn->query("DELETE FROM monthly_profit_report WHERE id=$id");

    header("Location: monthly_profit_report_list.php");
    exit();
}
?>