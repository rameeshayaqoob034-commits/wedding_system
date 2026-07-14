<?php
$conn = new mysqli("localhost", "root", "", "wedding_system");
if($conn->connect_error){ die("Connection failed: ".$conn->connect_error); }

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id <= 0){ die("Invalid Booking ID. <a href='booking_list.php'>Back</a>"); }

$result = $conn->query("SELECT * FROM bookings WHERE id='$id'");
if($result->num_rows == 0){ die("Booking not found. <a href='booking_list.php'>Back</a>"); }

$row = $result->fetch_assoc();

if(isset($_POST['update'])){
    $client_name = $_POST['client_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $event_type = $_POST['event_type'] ?? '';
    $hall = $_POST['hall'] ?? '';
    $guest_count = $_POST['guest_count'] ?? 0;
    $hall_charges = $_POST['hall_charges'] ?? 0;
    $advance_payment = $_POST['advance_payment'] ?? 0;
    $payment_status = $_POST['payment_status'] ?? 'Pending';
    $Decor = $_POST['Decor'] ?? '';
    $Heater = $_POST['Heater'] ?? '';
    $Ac = $_POST['Ac'] ?? '';
    $Tax_Amount = $_POST['Tax_Amount'] ?? 0;
    $Breakage = $_POST['Breakage'] ?? 0;
    $Payment_Type = $_POST['Payment_Type'] ?? '';

    $filename = $row['Receipt_image'];
    if(isset($_FILES['Receipt_image']) && $_FILES['Receipt_image']['name'] != ''){
        $filename = time().'_'.basename($_FILES['Receipt_image']['name']);
        move_uploaded_file($_FILES['Receipt_image']['tmp_name'], "uploads/".$filename);
    }

    $sql = "UPDATE bookings SET client_name='$client_name', phone='$phone', event_date='$event_date', event_type='$event_type', hall='$hall', guest_count='$guest_count', hall_charges='$hall_charges', advance_payment='$advance_payment', payment_status='$payment_status', Decor='$Decor', Heater='$Heater', Ac='$Ac', Tax_Amount='$Tax_Amount', Breakage='$Breakage', Payment_Type='$Payment_Type', Receipt_image='$filename' WHERE id='$id'";

    if($conn->query($sql)){
        echo "Booking updated successfully. <a href='booking_list.php'>Back to List</a>";
        $row = $conn->query("SELECT * FROM bookings WHERE id='$id'")->fetch_assoc();
    } else {
        echo "Error: ".$conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
</head>
<body>
<h2>Edit Booking</h2>
<form method="POST" enctype="multipart/form-data">
    Client Name: <input type="text" name="client_name" value="<?php echo htmlspecialchars($row['client_name']); ?>" required><br><br>
    Phone: <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required><br><br>
    Event Date: <input type="date" name="event_date" value="<?php echo $row['event_date']; ?>" required><br><br>
    Event Type: <input type="text" name="event_type" value="<?php echo htmlspecialchars($row['event_type']); ?>"><br><br>
    Hall: <input type="text" name="hall" value="<?php echo htmlspecialchars($row['hall']); ?>"><br><br>
    Guest Count: <input type="number" name="guest_count" value="<?php echo $row['guest_count']; ?>"><br><br>
    Hall Charges: <input type="number" name="hall_charges" value="<?php echo $row['hall_charges']; ?>"><br><br>
    Advance Payment: <input type="number" name="advance_payment" value="<?php echo $row['advance_payment']; ?>"><br><br>
    Payment Status:
    <select name="payment_status">
        <option value="Pending" <?php if($row['payment_status']=='Pending') echo 'selected'; ?>>Pending</option>
        <option value="Paid" <?php if($row['payment_status']=='Paid') echo 'selected'; ?>>Paid</option>
    </select><br><br>
    Decor: <input type="text" name="Decor" value="<?php echo htmlspecialchars($row['Decor']); ?>"><br><br>
    Heater: <input type="text" name="Heater" value="<?php echo htmlspecialchars($row['Heater']); ?>"><br><br>
    Ac: <input type="text" name="Ac" value="<?php echo htmlspecialchars($row['Ac']); ?>"><br><br>
    Tax Amount: <input type="number" name="Tax_Amount" value="<?php echo $row['Tax_Amount']; ?>"><br><br>
    Breakage: <input type="number" name="Breakage" value="<?php echo $row['Breakage']; ?>"><br><br>
    Payment Type: <input type="text" name="Payment_Type" value="<?php echo htmlspecialchars($row['Payment_Type']); ?>"><br><br>
    Receipt Image: <input type="file" name="Receipt_image"><br><br>
    <input type="submit" name="update" value="Update Booking">
</form>
</body>
</html>