<?php
//include database configuration file
require_once "dbConfig.php";

$postData = $statusMsg  = $valErr = "";
$status = "danger";


//if the form is submitted
if (isset($_POST['AddEvent'])) {

  //get event information
  $_SESSION['postData'] = $_POST;
  $title = !empty($_POST['title']) ? trim($_POST['title']) : '';
  $description = !empty($_POST['description']) ? trim($_POST['description']) : '';
  $location = !empty($_POST['location']) ? trim($_POST['location']) : '';
  $date = !empty($_POST['date']) ? trim($_POST['date']) : '';
  $time_from = !empty($_POST['time_from']) ? trim($_POST['time_from']) : '';
  $time_to = !empty($_POST['time_to']) ? trim($_POST['time_to']) : '';

  //validate form input fields
  if (empty($title)) {
    $valErr .= "Please enter event title <br>";
  }

  if (empty($date)) {
    $valErr .= "Please enter event date <br>";
  }

  //check weath user inputs are empty or not
  if (empty($valErr)) {

    //insert data into the database
    $sqlQ = "INSERT INTO events (title,location,description, date, time_from, time_to, created_at) VALUES (?,?,?,?,?,?,NOW())";
    $stmt = $DBConnections->prepare($sqlQ);
    $stmt->bind_param("ssssss", $db_title, $db_location, $db_description, $db_date, $db_time_form, $db_time_to);
    $db_title = $title;
    $db_description = $description;
    $db_time_form = $time_from;
    $db_time_to = $time_to;
    $db_date = $date;
    $db_location = $location;
    $insert = $stmt->execute();

    if ($insert) {
      $event_id = $stmt->insert_id;
      unset($_SESSION['postData']);

      //store event id session
      $_SESSION['last_event_id'] = $event_id;

      //redirect user for google authentication
      header("location: " . GOOGLE_OAUTH_URL);
      exit();
    } else {
      $status = "Something went wrong, Please try again after some time.";
    }
  } else {
    $statusMsg = "<p>Please fill all the mandatory fields:</p>" . trim($valErr, "<br>");
  }
} else {
  $statusMsg = "Form submission failed!";
}

$_SESSION['status_response'] = array(
  "status" => $status,
  "status_msg" => $statusMsg
);

header("location: index.php");
exit();
