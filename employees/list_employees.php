<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "wedding_system";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $conn->real_escape_string($_GET['delete_id']);


if (isset($_GET['delete_id'])) {
    $id_card = $conn->real_escape_string($_GET['delete_id']);

    // Get employee primary id from id_card
    $emp_res = $conn->query("SELECT id FROM employees WHERE id_card='$id_card'");
    if($emp_res->num_rows > 0){
        $emp = $emp_res->fetch_assoc();
        $emp_id = $emp['id'];

        // Delete child attendance first
        $conn->query("DELETE FROM employee_attendance WHERE employee_id='$emp_id'");

        // Delete employee
        if ($conn->query("DELETE FROM employees WHERE id='$emp_id'")) {
            header("Location: list_employees.php?deleted=1");
            exit;
        } else {
            echo "Error deleting employee: " . $conn->error;
        }
    } else {
        echo "Employee not found!";
    }
}


// Fetch all employees
$sql = "SELECT id_card, Name, Photo, Salary, Advance, Attendance, Phone, Agreement_letter 
        FROM employees 
        ORDER BY Name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee List</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body {font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0;}
.container {width:95%; margin:20px auto;}
table {width:100%; border-collapse:collapse; background:#fff; border-radius:8px; overflow:hidden;}
th, td {padding:10px 12px; text-align:left; font-size:14px;}
th {background:#34495e; color:#fff;}
tr:nth-child(even){background:#f4f4f4;}
a.button {text-decoration:none; padding:6px 12px; background:#e74c3c; color:#fff; border-radius:4px;}
a.button:hover {background:#c0392b;}
.attendance-btn {background:#2980b9; color:#fff; padding:4px 10px; border-radius:4px; text-decoration:none;}
.attendance-btn:hover {background:#1f618d;}
</style>
</head>
<body>
<div class="container">
    <h2>Employee List</h2>
    <?php if(isset($_GET['deleted'])) echo "<p style='color:green;'>Employee deleted successfully.</p>"; ?>
    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Salary</th>
                <th>Advance</th>
                <th>Attendance</th>
                <th>Agreement</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $photo = !empty($row['Photo']) ? "<img src='uploads/{$row['Photo']}' width='50' alt='Photo'>" : "";
                    $agreement = !empty($row['Agreement_letter']) ? "<a href='uploads/{$row['Agreement_letter']}' target='_blank'>View</a>" : "";
                    $id_card = $row['id_card'];
                    echo "<tr>
                            <td>$photo</td>
                            <td>{$row['Name']}</td>
                            <td>{$row['Phone']}</td>
                            <td>Rs. {$row['Salary']}</td>
                            <td>Rs. {$row['Advance']}</td>
                            <td><a class='attendance-btn' href='attendance.php?employee_id=$id_card'>View Attendance</a></td>
                            <td>$agreement</td>
                            <td>
                                <a class='button' href='edit_employee.php?edit_id=$id_card'>Edit</a>
                                <a class='button' href='list_employees.php?delete_id=$id_card' onclick=\"return confirm('Are you sure you want to delete this employee?');\">Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No employees found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>