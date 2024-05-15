<?php
$login = false;
$error = false;
$text = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';
    $email = $_POST['signin-email'];
$password = $_POST['signin-password'];

$sql = "SELECT * FROM `users` WHERE `email` = '$email'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $stored_hashed_password = $row['password'];
    if (password_verify($password, $stored_hashed_password)) {
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $row['email'];
        header("location: home.php");
        exit;
    } else {
        $error = true;
        $text = 'Password does not match';
    }
} else {
    $error = true;
    $text = 'User not found.';
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/signup.css">
    <title>SignIn</title>
</head>
<body>
<div class="sec">
    <section class="sec1">
        <span class="heading">SignIn</span>
        <form action="#" method="POST">
            <span class="input">
                <img src="./assets/email.png" alt="email-icon">
                <input type="email" placeholder="Enter your email" required name="signin-email">
            </span>
            <span class="input">
                <img src="./assets/password.png" alt="password-icon">
                <input type="password" placeholder="Enter your password" required name="signin-password">
            </span>
            <button type="submit" class="btn">SignIn</button>
            <?php
            if ($login) {
                echo '<span class="txt" role="alert" style=" color: lightgreen; text-align: center">';
                echo 'You have successfully logged in!';
                echo '</span>';}
            if ($error) {
                echo '<span class="txt" role="alert" style=" color: red; text-align: center">';
                echo $text;
                echo '</span>';}
            ?>
            <span class="txt">Forgot your password? <a href="passwordforgot.php" class="navigation">Click Here</a></span>
            <span class="txt">Don't have an account? <a href="signup.php" class="navigation">SignUp</a></span>
        </form>
    </section>
    <section class="sec2">
        <img src="./assets/signup.png" alt="signup-image" class="signup">
    </section>
</div>
</body>
</html>
