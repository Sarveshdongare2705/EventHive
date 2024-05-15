<?php
include 'dbconnect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    $eventId = $data->id;
    $title = $data->title;
    $desc = $data->desc;
    $event_date = $data->event_date;

    $sql = "UPDATE events SET title = '$title', `desc` = '$desc', event_date = '$event_date' WHERE id = $eventId";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        http_response_code(200); 
    } else {
        http_response_code(500); 
    }
}
?>
