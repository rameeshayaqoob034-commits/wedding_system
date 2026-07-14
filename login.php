<?php
session_start();
require_once('config.php');

$error = '';
$success = '';

// --- LOGIN ---
if (isset($_POST['login'])) {
    $username = trim($conn->real_escape_string($_POST['username']));
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE username='$username'";
    $result = $conn->query($query);

    if(!$result){
        $error = "SQL Error: " . $conn->error;
    } elseif ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        // Check plain text OR hashed password
        if ($password == $admin['password'] || password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Incorrect Password";
        }
    } else {
        $error = "Username not found";
    }
}

// --- CHANGE USERNAME ---
if(isset($_POST['change_username'])){
    $new_username = trim($conn->real_escape_string($_POST['new_username']));
    $admin_id = $_SESSION['admin_id'] ?? 1;

    if(!preg_match("/^[a-zA-Z0-9_]+$/", $new_username)){
        $error = "Username can only contain letters, numbers, and underscore!";
    } else {
        $updateQuery = "UPDATE admins SET username='$new_username' WHERE id='$admin_id'";
        if($conn->query($updateQuery)){
            if($conn->affected_rows > 0){
                $_SESSION['admin_username'] = $new_username;
                $success = "Username updated successfully";
            } else {
                $error = "Update failed: no rows affected";
            }
        } else {
            $error = "SQL Error: " . $conn->error;
        }
    }
}

// --- CHANGE PASSWORD ---
if(isset($_POST['change_password'])){
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $admin_id = $_SESSION['admin_id'] ?? 1;

    $updateQuery = "UPDATE admins SET password='$new_password' WHERE id='$admin_id'";
    if($conn->query($updateQuery)){
        if($conn->affected_rows > 0){
            $success = "Password updated successfully";
        } else {
            $error = "Password update failed: no rows affected";
        }
    } else {
        $error = "SQL Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login - The Wedding Avenue Chakwal</title>
<style>
body {font-family: Arial; background:#ecf0f1; display:flex; align-items:center; justify-content:center; height:100vh; margin:0;}
.login-container {background:#fff; padding:30px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.2); width:350px;}
.login-container h2 {text-align:center; margin-bottom:20px; color:#e74c3c;}
.login-container input, .login-container button {width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;}
.login-container button {background:#27ae60; color:#fff; border:none; cursor:pointer;}
.login-container button:hover {background:#2ecc71;}
.error {color:red; text-align:center; margin-bottom:10px;}
.success {color:green; text-align:center; margin-bottom:10px;}
.modal {display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);}
.modal-content {background:#fff; width:400px; margin:100px auto; padding:20px; border-radius:8px; position:relative;}
.modal-content span {position:absolute; top:10px; right:15px; cursor:pointer; font-weight:bold;}
.modal-content input {width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:1px solid #ccc;}
.modal-content button {width:100%; padding:10px; background:#27ae60; color:#fff; border:none; border-radius:5px; cursor:pointer;}
.modal-content button:hover {background:#2ecc71;}
</style>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>
    <?php if($error!=''){ echo "<div class='error'>$error</div>"; } ?>
    <?php if($success!=''){ echo "<div class='success'>$success</div>"; } ?>

    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <!-- Buttons to open modals -->
    <button onclick="document.getElementById('usernameModal').style.display='block'">Change Username</button>
    <button onclick="document.getElementById('passwordModal').style.display='block'">Change Password</button>
</div>

<!-- Change Username Modal -->
<div id="usernameModal" class="modal">
    <div class="modal-content">
        <span onclick="document.getElementById('usernameModal').style.display='none'">&times;</span>
        <h3>Change Username</h3>
        <form method="post" action="">
            <input type="text" name="new_username" placeholder="New Username" required>
            <button type="submit" name="change_username">Update Username</button>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <span onclick="document.getElementById('passwordModal').style.display='none'">&times;</span>
        <h3>Change Password</h3>
        <form method="post" action="">
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit" name="change_password">Update Password</button>
        </form>
    </div>
</div>

<script>
window.onclick = function(event) {
    if(event.target.className === 'modal'){
        event.target.style.display = "none";
    }
}
</script>

</body>
</html>