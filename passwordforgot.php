<?php
session_start();

$success = false;
$error = false;
$text = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';
    
    $email = $_POST['signin-email'];
    $newPassword = $_POST['signin-password'];
    if (strlen($newPassword) < 8 || !preg_match("/[A-Z]/", $newPassword) || !preg_match("/[a-z]/", $newPassword) || !preg_match("/[0-9\W]/", $newPassword)) {
        $error = true;
        $text = 'Weak password. Password must be at least 8 characters long and include uppercase letters, lowercase letters, and numbers.';
    }

    $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($error == false && $result && mysqli_num_rows($result) > 0) {
        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE `users` SET `password` = '$hashed_password' WHERE `email` = '$email'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $success = true;
            $text = "Password updated successfully!";
        } else {
            $error = true;
            $text = "Failed to update password.";
        }
    }
    else{
        $error = true;
        $text = 'User not found';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/signup.css">
    <title>Change Password</title>
</head>
<body>
<div class="sec">
    <section class="sec1">
        <span class="heading">Change Password</span>
        <form action="#" method="POST">
            <span class="input">
                <img src="./assets/email.png" alt="email-icon">
                <input type="email" placeholder="Enter your email" required name="signin-email">
            </span>
            <span class="input">
                <img src="./assets/password.png" alt="password-icon">
                <input type="password" placeholder="Enter your new password" required name="signin-password">
            </span>
            <button type="submit" class="btn">Change Password</button>
            <?php
            if ($success) {
                echo '<span class="txt" role="alert" style="color: lightgreen; text-align: center">';
                echo $text;
                echo '</span>';
            }
            if ($error) {
                echo '<span class="txt" role="alert" style="color: red; text-align: center">';
                echo $text;
                echo '</span>';
            }
            ?>
            <span class="txt">Go back to ? <a href="signin.php" class="navigation">SignIn</a></span>
        </form>
    </section>
    <section class="sec2">
        <img src="./assets/signup.png" alt="signup-image" class="signup">
    </section>
</div>
</body>
</html>
