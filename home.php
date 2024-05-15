<?php 
session_start();
$loggedin = false;
include 'dbconnect.php';
if(isset($_SESSION['loggedin'])){
    $loggedin = true;
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    }
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
        $query = "SELECT * FROM events WHERE email = '$email' AND event_date = '$date'";
        $result = mysqli_query($conn, $query);
    
        $events = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
        echo json_encode($events);
        exit;
    }
    

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $event_date = $_POST['event_date'];

    $query = "INSERT INTO `events`(`email`, `title`, `desc`, `event_date`) VALUES ('$email','$title', '$desc', '$event_date')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("location: home.php");
    } else {
        header("location: home.php");
    }
    exit;
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/home.css">
    <title>Home</title>
</head>
<body onload="initializeCalendar()">
    <?php 
    if($loggedin){echo '<div id="dob">'.$row['dob'].'</div>';}
    ?>
    <section class="header">
        <span href="home.php" class="logo">EventHive.</span>
        <nav class="navbar">
            <a  href="home.php" class="navbar_buttons">Home</a>
            <a  href="#" class="navbar_buttons">About</a>
        </nav>
        <div class="buttons">
            <?php if($loggedin){echo $row['username'];} ?>
            <?php if($loggedin){echo '<a href="logout.php" class = "btn">Logout</a>';}else{echo '<a href="signup.php" class="btn";">SignUp</a>';echo '<a href="signin.php" class="btn">SignIn</a>';} ?>
        </div>
    </section>
    <div class="content">
    <div class="calendar-container">
        <label for="month">Select Month:</label>
        <input type="month" id="month" onchange="generateCalendar()">
        <div class="days">
        <div class="calendar-day1">Sun</div>
        <div class="calendar-day1">Mon</div>
        <div class="calendar-day1">Tue</div>
        <div class="calendar-day1">Wed</div>
        <div class="calendar-day1">Thu</div>
        <div class="calendar-day1">Fri</div>
        <div class="calendar-day1">Sat</div>
        </div>
        <div id="calendar"></div>
    </div>
    <div class="form-container">
        <h2>Add Event</h2>
        <form id="eventForm" action="#" method="POST">
            <input type="text" id="eventTitle" placeholder="Event Title" required name="title">
            <textarea id="eventDescription" placeholder="Event Description" name="desc"></textarea>
            <input type="date" id="eventDate" required name="event_date">
            <?php if($loggedin){echo '<button type="submit" class="submitbtn">Add Event</button>';} else{echo '<button class="submitbtn">Login to Create Event</button>';} ?>
        </form>
    </div>
    </div>
    <script src="script.js"></script>
</body>
</html>