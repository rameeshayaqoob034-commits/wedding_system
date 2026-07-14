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

// Handle form submission
if(isset($_POST['submit'])){
    $employee_id = $_POST['employee_id'];
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    // Check if record exists
    $check = $conn->query("SELECT * FROM employee_attendance WHERE employee_id='$employee_id' AND attendance_date='$attendance_date'");
    if($check->num_rows > 0){
        // Update existing record
        $conn->query("UPDATE employee_attendance SET status='$status', remarks='$remarks' WHERE employee_id='$employee_id' AND attendance_date='$attendance_date'");
        $msg = "Attendance updated successfully!";
    }else{
        // Insert new record
        $conn->query("INSERT INTO employee_attendance (employee_id, attendance_date, status, remarks) VALUES ('$employee_id','$attendance_date','$status','$remarks')");
        $msg = "Attendance recorded successfully!";
    }
}

// Fetch employees for dropdown
$employees = $conn->query("SELECT id, Name FROM employees ORDER BY Name ASC");

// Fetch last 10 attendance records
$attendance_list = $conn->query("SELECT ea.*, e.Name FROM employee_attendance ea 
                                 JOIN employees e ON e.id = ea.employee_id
                                 ORDER BY attendance_date DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Attendance</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
* {margin:0; padding:0; box-sizing:border-box; font-family: Arial, sans-serif;}
body {background:#ecf0f1;}
.wrapper {max-width:1000px; margin:30px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1);}
h2 {text-align:center; margin-bottom:20px; color:#e74c3c;}
form {display:flex; flex-wrap:wrap; gap:15px; margin-bottom:30px;}
form label {flex-basis:100%; font-weight:bold; margin-bottom:5px;}
form input, form select, form textarea {padding:8px; width:100%; border:1px solid #ccc; border-radius:5px;}
form textarea {resize:none;}
form button {padding:10px 20px; background:#e74c3c; color:#fff; border:none; border-radius:5px; cursor:pointer;}
form button:hover {background:#c0392b;}
table {width:100%; border-collapse:collapse; background:#fff;}
table th, table td {padding:10px; border:1px solid #ccc; text-align:left;}
table th {background:#34495e; color:#fff;}
table tr:nth-child(even) {background:#f4f4f4;}
.msg {margin-bottom:15px; padding:10px; background:#27ae60; color:#fff; border-radius:5px;}
</style>
</head>
<body>
<div class="wrapper">
    <h2>Employee Attendance</h2>

    <?php if(isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

    <!-- Attendance Form -->
    <form method="POST">
        <div style="flex:1;">
            <label>Employee</label>
            <select name="employee_id" required>
                <option value="">-- Select Employee --</option>
                <?php while($row = $employees->fetch_assoc()){ ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div style="flex:1;">
            <label>Date</label>
            <input type="date" name="attendance_date" value="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div style="flex:1;">
            <label>Status</label>
            <select name="status" required>
                <option value="">-- Select Status --</option>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Leave">Leave</option>
            </select>
        </div>

        <div style="flex:2;">
            <label>Remarks (Optional)</label>
            <textarea name="remarks" rows="1"></textarea>
        </div>

        <div style="flex:1; align-self:flex-end;">
            <button type="submit" name="submit">Save Attendance</button>
        </div>
    </form>

    <!-- Last 10 Attendance Records -->
    <h2>Recent Attendance</h2>
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Date</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($attendance_list->num_rows > 0){
                while($row = $attendance_list->fetch_assoc()){
                    echo "<tr>
                            <td>".$row['Name']."</td>
                            <td>".$row['attendance_date']."</td>
                            <td>".$row['status']."</td>
                            <td>".$row['remarks']."</td>
                          </tr>";
                }
            }else{
                echo "<tr><td colspan='4'>No attendance records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>