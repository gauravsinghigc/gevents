<?php
//include db configuration file
require_once "dbConfig.php";

//include Google Calendar api handler class
include_once "GoogleCalendarApi.Class.php";

$statusMsg = "";
$status = "danger";

if (isset($_GET['code'])) {
  //Initialize Google Calendar API Class.
  $GoogleCalendarApi = new GoogleCalendarApi();

  //get event id from the session
  $event_id = $_SESSION['last_event_id'];

  if (!empty($event_id)) {

    //fetch event data from Database
    $sqlQ = "SELECT * FROM events where id=?";
    $stmt = $DBConnections->prepare($sqlQ);
    $stmt->bind_param("i", $db_event_id);
    $db_event_id = $event_id;
    $stmt->execute();
    $results = $stmt->get_result();
    $eventData = $results->fetch_assoc();

    if (!empty($eventData)) {
      $calendar_event = array(
        "summary" => $eventData['title'],
        "location" => $eventData['location'],
        "description" => $eventData['description'],
      );

      $event_datetime = array(
        "event_date" => $eventData['date'],
        "start_time" => $eventData['time_from'],
        "end_time" => $eventData['time_to'],
      );

      //get the access token
      if (!empty($_SESSION['google_access_token'])) {
        $access_token = $_SESSION['google_access_token'];
      } else {
        $data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URL, GOOGLE_CLIENT_SECRET, $_GET['code']);
        $access_token = $data['access_token'];
        $_SESSION['google_access_token'] = $access_token;
      }

      if (!empty($access_token)) {
        try {
          //Get user calendar timezone
          $user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);

          //create an event on primary calendar
          $google_event_id = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event, 0, $event_datetime, $user_timezone);

          if ($google_event_id) {

            //update google event refrence in the database;
            $sqlQ = "UPDATE events SET google_calendar_event_id = ? where id=?";
            $stmt = $DBConnections->prepare($sqlQ);
            $stmt->bind_param("si", $db_google_event_id, $db_event_id);
            $db_google_event_id = $google_event_id;
            $db_event_id = $event_id;
            $update = $stmt->execute();

            unset($_SESSION['last_event_id']);
            unset($_SESSION['google_access_token']);

            $status = "success";
            $statusMsg = "<p>Event #$event_id has been added to Google Calendar Successfully!</p>";
            $statusMsg .= "<p><a href='https://calendar.google.com/calendar'>Open Calendar</a></p>";
          }
        } catch (Exception $e) {
          $statusMsg = $e->getMessage();
        }
      } else {
        $statusMsg = "failed to fetch access token";
      }
    } else {
      $statusMsg = "Event data not found!";
    }
  } else {
    $statusMsg = "Event reference not found!";
  }

  $_SESSION['status_response'] = array("status" => $status, "status_msg" => $statusMsg);
  header("location: index.php");
  exit();
}
