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

// Fetch data
$totalBookings = $conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'];
$totalRevenue = $conn->query("SELECT SUM(hall_charges) as revenue FROM bookings")->fetch_assoc()['revenue'];
$pendingBookings = $conn->query("SELECT COUNT(*) as pending FROM bookings WHERE event_status='Pending'")->fetch_assoc()['pending'];

// Upcoming events for table
$upcomingEvents = $conn->query("SELECT * FROM bookings WHERE event_date >= CURDATE() ORDER BY event_date ASC")->fetch_all(MYSQLI_ASSOC);

// Fetch all booked dates for calendar highlight
$bookedDatesRes = $conn->query("SELECT event_date, client_name, event_type, hall, guest_count, advance_payment, event_status, event_time FROM bookings");
$bookedDates = [];
while($row = $bookedDatesRes->fetch_assoc()){
    $bookedDates[$row['event_date']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>The Wedding Avenue Chakwal Dashboard</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
* {margin:0; padding:0; box-sizing:border-box; font-family: 'Arial', sans-serif;}
.wrapper {display:flex; min-height:100vh;}

/* Sidebar */
.sidebar {width:220px; background:#2c3e50; color:#fff; display:flex; flex-direction:column;}
.sidebar-header {text-align:center; padding:20px 0; background:#1a252f;}
.sidebar-header img {width:60px; border-radius:50%; margin-bottom:10px;}
.sidebar-header h3 {font-size:16px; font-weight:bold;}
.sidebar-header h3 a {color:white; text-decoration:none;}
.sidebar-menu {list-style:none; padding:0; margin-top:20px; flex-grow:1;}
.sidebar-menu li {padding:12px 20px; position:relative;}
.sidebar-menu li a {color:#fff; text-decoration:none; display:flex; align-items:center;}
.sidebar-menu li a i {margin-right:10px;}
.sidebar-menu li a:hover {background:#34495e; border-radius:5px;}

/* Dropdown Menu */
.dropdown-content { display: none; position: relative; list-style-type: none; padding-left: 0; }
.dropdown-content li a { padding-left: 35px; display: block; color: #fff; text-decoration: none; }
.dropdown-content li a:hover { background-color: #34495e; border-radius:5px; }
.dropdown:hover .dropdown-content { display: block; }

/* Main Content */
.main-content {flex:1; padding:15px; background:#ecf0f1; display:flex; flex-direction:column;}

/* Header Top */
.header-top {display:flex; align-items:center; justify-content:space-between; padding:10px 15px; flex-wrap:wrap;}
.header-left {display:flex; align-items:center; gap:10px;}
.header-left img {width:60px;}
.header-left .project-text {font-size:14px; font-weight:bold; color:#e74c3c;}
.header-center {color:#e74c3c; font-family:'Lucida Handwriting', cursive; font-size:28px; text-align:center; flex-grow:1; margin:0;}

/* Dashboard Cards */
.dashboard-cards {display:flex; gap:15px; margin-bottom:20px;}
.card {background:#fff; padding:15px; border-radius:8px; flex:1; text-align:center; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
.card h3 {margin-bottom:8px; font-size:16px; color:#333;}
.card p {font-size:20px; font-weight:bold; color:#27ae60;}
canvas {background:#fff; border-radius:8px; padding:10px; max-height:200px;}

/* Table */
.dashboard-table table {width:100%; border-collapse:collapse; background:#fff; border-radius:8px; overflow:hidden; margin-bottom:15px;}
.dashboard-table th, .dashboard-table td {padding:10px 12px; text-align:left; font-size:14px;}
.dashboard-table th {background:#34495e; color:#fff;}
.dashboard-table tr:nth-child(even) {background:#f4f4f4;}

/* Calendar */
.calendar {background:#fff; padding:15px; border-radius:8px; margin-bottom:20px;}
.calendar h3 {text-align:center; margin-bottom:10px; color:#e74c3c;}
.calendar-nav {display:flex; justify-content:space-between; margin-bottom:10px;}
.calendar-grid {display:grid; grid-template-columns:repeat(7,1fr); gap:5px;}
.calendar-day, .calendar-cell {padding:10px; text-align:center; border-radius:5px;}
.calendar-day {background:#34495e; color:#fff; font-weight:bold;}
.calendar-cell {background:#ecf0f1; cursor:pointer; position:relative;}
.calendar-cell.booked {background:#f1c40f; color:#000; font-weight:bold;}

/* Modal */
.modal {display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;}
.modal-content {background:#fff; padding:20px; border-radius:8px; max-width:600px; width:90%; max-height:80%; overflow-y:auto;}
.modal-content h3 {margin-bottom:10px;}
.modal-content table {width:100%; border-collapse:collapse;}
.modal-content th, .modal-content td {border:1px solid #ccc; padding:8px; text-align:left;}
.modal-content th {background:#34495e; color:#fff;}
.modal-content td {background:#f9f9f9;}
.close-modal {float:right; cursor:pointer; color:#e74c3c; font-weight:bold;}
</style>
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="login.php"><img src="images/admin_logo.jpg" alt="Admin Logo"></a>
            <h3><a href="login.php">Admin</a></h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#"><i class="fa fa-check"></i> Checkings</a></li>
            <li><a href="add_booking.php"><i class="fa fa-calendar"></i> Bookings</a></li>

            <!-- Employees Dropdown -->
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"><i class="fa fa-user"></i> Employees ▾</a>
                <ul class="dropdown-content">
                    <li><a href="employees/add_employee.php">Add Employee</a></li>
                    <li><a href="employees/list_employees.php">Employee List</a></li>
                    <li><a href="employees/attendance.php">Employee Attendance</a></li>
                </ul>
            </li>

            <!-- Expenses Dropdown -->
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"><i class="fa fa-money-bill"></i> Expenses ▾</a>
                <ul class="dropdown-content">
                    <li><a href="expenses/add_expense.php">Add Expense</a></li>
                    <li><a href="expenses/expenses_list.php">Expenses List</a></li>
                    <li><a href="expenses/monthly_profit_report.php">
                    Monthly Profit Calculation
                </a></li>

                <li><a href="expenses/monthly_profit_report_list.php">
                    Monthly Profit Report
                </a></li>
                </ul>
            </li>

          <li class="dropdown">
    <a href="javascript:void(0)" class="dropbtn">
        <i class="fa fa-chart-line"></i> Reports ▾
    </a>
    <ul class="dropdown-content">
        <li><a href="reports/daily_report.php">Daily Report</a></li>
    </ul>
</li>
            <li><a href="#"><i class="fa fa-file-invoice"></i> Invoices</a></li>
            <li><a href="#"><i class="fa fa-plus"></i> Add Items</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header Top -->
        <div class="header-top">
            <div class="header-left">
                <img src="images/logo.jpg" alt="Wedding Logo">
                <div class="project-text">The Project By</div>
            </div>
            <div class="header-center">The Wedding's Avenue Chakwal</div>
        </div>

        <!-- Dashboard Cards -->
        <section class="dashboard-cards">
            <div class="card"><h3>Total Bookings</h3><p><?php echo $totalBookings; ?></p></div>
            <div class="card"><h3>Total Revenue</h3><p>Rs. <?php echo $totalRevenue; ?></p></div>
            <div class="card"><h3>Pending Bookings</h3><p><?php echo $pendingBookings; ?></p></div>
        </section>

        <!-- Dashboard Chart -->
        <section class="dashboard-charts">
            <canvas id="bookingsChart"></canvas>
        </section>

        <!-- Calendar Section -->
        <section class="calendar">
            <h3>Event Calendar</h3>
            <div class="calendar-nav">
                <button id="prevMonth">&lt; Prev</button>
                <div id="calendarMonthYear"></div>
                <button id="nextMonth">Next &gt;</button>
            </div>
            <div class="calendar-grid" id="calendarGrid"></div>
        </section>

        <!-- Upcoming Events -->
        <section class="dashboard-table">
            <h2>Upcoming Events</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client Name</th>
                        <th>Event Date</th>
                        <th>Advance Payment</th>
                        <th>Event Status</th>
                        <th>Hall Charges</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(!empty($upcomingEvents)){
                        foreach($upcomingEvents as $row){
                            echo "<tr>
                                    <td>".($row['id'] ?? '')."</td>
                                    <td>".($row['client_name'] ?? '')."</td>
                                    <td>".($row['event_date'] ?? '')."</td>
                                    <td>Rs. ".($row['advance_payment'] ?? '')."</td>
                                    <td>".($row['event_status'] ?? '')."</td>
                                    <td>Rs. ".($row['hall_charges'] ?? '')."</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No upcoming events</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</div>

<script>
// Chart.js
const ctx = document.getElementById('bookingsChart').getContext('2d');
const bookingsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Total', 'Pending', 'Upcoming'],
        datasets: [{
            label: 'Bookings Overview',
            data: [<?php echo $totalBookings; ?>, <?php echo $pendingBookings; ?>, <?php echo count($upcomingEvents); ?>],
            backgroundColor: ['#27ae60','#e74c3c','#2980b9']
        }]
    },
    options: { responsive:true, plugins:{ legend:{ display:false } } }
});

// Calendar Logic
let bookedDates = <?php echo json_encode($bookedDates); ?>;
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

function renderCalendar(month, year){
    const calendarGrid = document.getElementById('calendarGrid');
    calendarGrid.innerHTML = '';
    document.getElementById('calendarMonthYear').innerText = new Date(year, month).toLocaleString('default', { month:'long', year:'numeric' });

    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month+1,0).getDate();

    const days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    for(let d of days){
        const dayCell = document.createElement('div');
        dayCell.classList.add('calendar-day');
        dayCell.innerText = d;
        calendarGrid.appendChild(dayCell);
    }

    for(let i=0;i<firstDay;i++){
        const blankCell = document.createElement('div');
        blankCell.classList.add('calendar-cell');
        calendarGrid.appendChild(blankCell);
    }

    for(let date=1; date<=lastDate; date++){
        const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(date).padStart(2,'0')}`;
        const cell = document.createElement('div');
        cell.classList.add('calendar-cell');
        cell.innerText = date;
        if(bookedDates[dateStr]) cell.classList.add('booked');
        cell.addEventListener('click', ()=>{
            if(bookedDates[dateStr]){
                const tbody = document.querySelector('#modalTable tbody');
                tbody.innerHTML = '';
                bookedDates[dateStr].forEach(ev=>{
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${ev.client_name}</td>
                                    <td>${ev.event_type}</td>
                                    <td>${ev.hall}</td>
                                    <td>${ev.guest_count}</td>
                                    <td>Rs. ${ev.advance_payment}</td>
                                    <td>${ev.event_status}</td>
                                    <td>${ev.event_time}</td>`;
                    tbody.appendChild(tr);
                });
                document.getElementById('modalDate').innerText = dateStr;
                document.getElementById('eventModal').style.display = 'flex';
            }
        });
        calendarGrid.appendChild(cell);
    }
}

document.getElementById('prevMonth').addEventListener('click', ()=>{
    currentMonth--;
    if(currentMonth<0){currentMonth=11; currentYear--;}
    renderCalendar(currentMonth,currentYear);
});
document.getElementById('nextMonth').addEventListener('click', ()=>{
    currentMonth++;
    if(currentMonth>11){currentMonth=0; currentYear++;}
    renderCalendar(currentMonth,currentYear);
});

// Close modal
document.getElementById('closeModal').addEventListener('click', ()=>{
    document.getElementById('eventModal').style.display='none';
});

// Initial render
renderCalendar(currentMonth,currentYear);
</script>
</body>
</html>
