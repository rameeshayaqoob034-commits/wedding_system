<?php
$conn = new mysqli("localhost","root","","wedding_system");

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $sql = "DELETE FROM employees WHERE id='$id'";

    if($conn->query($sql)){
        header("Location: list_employees.php?msg=deleted");
    }else{
        echo "Error deleting record";
    }
}
?>