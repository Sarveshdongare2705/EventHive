<?php
$showalert = false;
$showerror = false;
$text = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';

    $username = $_POST["signup-name"];
    $email = $_POST["signup-email"];
    $password = $_POST['signup-password'];
    $cpassword = $_POST['confirm-password'];
    $sql = "SELECT * from `users` where 'email'= '$email'";
    $result1 = mysqli_query($conn , $sql);
    if($result1 && mysqli_num_rows($result1)>0){
        $showerror = true;
        $text = 'User already exists';
    }
    $dob = $_POST['dob_date'];
    if (!preg_match("/^[a-zA-Z]/", $username)) {
        $showerror = true;
        $text = 'Username must start with a letter.';
    }
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9\W]/", $password)) {
        $showerror = true;
        $text = 'Weak password. Password must be at least 8 characters long and include uppercase letters, lowercase letters, and numbers.';
    } elseif ($password != $cpassword) {
        $showerror = true;
        $text = 'Confirm password does not match with entered password.';
    }
    if (!$showerror) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`username`, `email`, `password` ,`dob`) VALUES ('$username', '$email', '$hashed_password' , '$dob')";
        $query = "INSERT INTO `events`(`email`, `title`, `desc`, `event_date`) VALUES ('$email','My Birthday', 'Happy Birthday $username!', '$dob')";
        $result = mysqli_query($conn, $sql);
        $result2 = mysqli_query($conn, $query);
        if ($result && $result2) {
            $showalert = true;
        } else {
            $showerror = true;
            $text = 'Error occurred while registering. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/signup.css">
    <style>
    </style>
    <title>SignUp</title>
</head>
<body>
    <div class="sec">
        <section class="sec1">
            <span class="heading">SignUp</span>
            <form action="#" method="POST">
                <span class="input">
                    <Img src="./assets/user.png"></Img>
                    <input type="text" placeholder="Enter your name" required  name="signup-name">
                </span>
                <span class="input">
                    <Img src="./assets/email.png"></Img>
                    <input type="email" placeholder="Enter your email" required  name="signup-email">
                </span>
                <span class="input">
                    <Img src="./assets/password.png"></Img>
                    <input type="password" placeholder="Enter your password" required  name="signup-password">
                </span>
                <span class="input">
                    <Img src="./assets/password.png"></Img>
                    <input type="password" placeholder="Confirm password" required  name="confirm-password">
                </span>
                <span class="input">
                    <Img src="./assets/dob.png"></Img>
                    <input type="date" id="eventDate" required name="dob_date">
                </span>
                <button class="btn">
                    SignUp
                </button>
                <?php
            if ($showalert) {
                echo '<span class="txt" role="alert" style=" color: lightgreen; text-align: center">';
                echo 'You have successfully registered';
                echo '</span>';}
            if ($showerror) {
                echo '<span class="txt" role="alert" style=" color: red; text-align: center">';
                echo $text;
                echo '</span>';}
            ?>
                <span class="txt">Already have an account ? <a href="signin.php" class="navigation">SignIn</a></span>
            </form>
        </section>
        <section class="sec2">
            <Img src="./assets/signup.png" class="signup"></Img>
        </section>
    </div>
</body>
</html>
