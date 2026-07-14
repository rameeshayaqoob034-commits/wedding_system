<?php
// Show all PHP errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$conn = new mysqli("localhost", "root", "", "wedding_system");
if($conn->connect_error){ die("Connection failed: ".$conn->connect_error); }

if(isset($_POST['submit'])){
    $client_name = $_POST['client_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $event_type = $_POST['event_type'] ?? '';
    $hall = $_POST['hall'] ?? '';
    $guest_count = $_POST['guest_count'] ?? 0;
    $hall_charges = $_POST['hall_charges'] ?? 0;
    $advance_payment = $_POST['advance_payment'] ?? 0;
    $payment_status = $_POST['payment_status'] ?? 'Pending';
    $event_status = $_POST['event_status'] ?? 'Pending';
    $Decor = $_POST['Decor'] ?? '';
    $Heater = $_POST['Heater'] ?? '';
    $Ac = $_POST['Ac'] ?? '';
    $Tax_Amount = $_POST['Tax_Amount'] ?? 0;
    $Breakage = $_POST['Breakage'] ?? 0;
    $Payment_Type = $_POST['Payment_Type'] ?? '';

    // Generate unique event_id
    $event_id = "EV".time();

    // Handle receipt upload
    $filename = '';
    if(isset($_FILES['Reciept_image']) && $_FILES['Reciept_image']['name'] != ''){
        $filename = time().'_'.basename($_FILES['Reciept_image']['name']);
        move_uploaded_file($_FILES['Reciept_image']['tmp_name'], "uploads/".$filename);
    }

    $sql = "INSERT INTO bookings (event_id, client_name, phone, event_date, event_time, event_type, hall, guest_count, hall_charges, advance_payment, payment_status, event_status, Decor, Heater, Ac, Tax_Amount, Breakage, Payment_Type, Reciept_image)
            VALUES ('$event_id','$client_name','$phone','$event_date','$event_time','$event_type','$hall','$guest_count','$hall_charges','$advance_payment','$payment_status','$event_status','$Decor','$Heater','$Ac','$Tax_Amount','$Breakage','$Payment_Type','$filename')";

    if($conn->query($sql)){
        echo "<script>alert('Booking Added Successfully');window.location='booking_list.php';</script>";
    } else {
        echo "Error: ".$conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Booking</title>
<style>
body{
    font-family:Arial;
    background:linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url("images/marquee.jfif");
    background-size:contain;
    background-position:center;
    background-repeat:no-repeat;
    background-attachment: fixed;
    margin:0;
    padding:0;
}

.container{
    width:650px;
    margin:40px auto;
    background:rgba(255,255,255,0.95);
    padding:30px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.4);
}

h2{text-align:center;color:#0a1f44;}

label{font-weight:bold; display:block; margin-top:12px;}
input,select{width:100%; padding:10px; margin-top:5px; border-radius:6px; border:1px solid #ccc;}
input:focus,select:focus{border-color:#3498db; outline:none;}

button{
    background:#0a1f44; color:white; border:none; padding:12px; width:100%;
    border-radius:6px; font-size:16px; cursor:pointer; margin-top:20px;
}
button:hover{background:#142f66;}

.top-btn{ text-align:right; margin-bottom:15px;}
.top-btn a button{width:auto; padding:10px 20px;}
</style>

<script>
function toggleReceiptField(){
    var paymentType=document.getElementById("Payment_Type").value;
    var receiptDiv=document.getElementById("receiptDiv");
    receiptDiv.style.display = (paymentType==="Bank") ? "block" : "none";
}
</script>
</head>
<body>

<div class="container">

<div class="top-btn">
<a href="booking_list.php"><button>Go to Booking List</button></a>
</div>

<h2>Add Booking</h2>

<form method="POST" enctype="multipart/form-data">

<label>Client Name</label>
<input type="text" name="client_name" required>

<label>Phone</label>
<input type="text" name="phone" required>

<label>Event Date</label>
<input type="date" name="event_date" required>

<label>Event Time</label>
<select name="event_time">
<option value="Day">Day</option>
<option value="Night">Night</option>
</select>

<label>Event Type</label>
<input type="text" name="event_type">

<label>Hall</label>
<input type="text" name="hall">

<label>Guest Count</label>
<input type="number" name="guest_count">

<label>Hall Charges</label>
<input type="number" name="hall_charges">

<label>Advance Payment</label>
<input type="number" name="advance_payment">

<label>Payment Status</label>
<select name="payment_status">
<option value="Pending">Pending</option>
<option value="Paid">Paid</option>
</select>

<label>Event Status</label>
<select name="event_status">
<option value="Pending">Pending</option>
<option value="Confirmed">Confirmed</option>
<option value="Cancelled">Cancelled</option>
</select>

<label>Decor</label>
<input type="text" name="Decor">

<label>Heater</label>
<input type="text" name="Heater">

<label>AC</label>
<input type="text" name="Ac">

<label>Tax Amount</label>
<input type="number" name="Tax_Amount">

<label>Breakage</label>
<input type="number" name="Breakage">

<label>Payment Type</label>
<select name="Payment_Type" id="Payment_Type" onchange="toggleReceiptField()">
<option value="Cash">Cash</option>
<option value="Bank">Bank</option>
</select>

<div id="receiptDiv" style="display:none;">
<label>Receipt Image</label>
<input type="file" name="Reciept_image">
</div>

<button type="submit" name="submit">Add Booking</button>
</form>
</div>

<script>toggleReceiptField();</script>
</body>
</html>