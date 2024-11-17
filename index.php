<?php
//include configurations files

include_once "config.php";
$status = $statusMsg = "";
if (!empty($_SESSION['status_response'])) {
  $status_response = $_SESSION['status_response'];
  $status = $status_response['status'];
  $statusMsg = $status_response['status_msg'];
  unset($_SESSION['status_response']);
}

$postData = "";
if (!empty($_SESSION['postData'])) {
  $postData = $_SESSION['postData'];
  unset($_SESSION['postData']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>G Calendar Alerts </title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</head>

<body>
  <section class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="p-2">
          <h4>Google Calendar Alerts</h4>
        </div>
      </div>
      <div class="col-md-6">
        <div class="shadow-sm">
          <h3 class="bold">ADD EVENT TO GOOGLE CALENDAR</h3>

          <div class="wrapper">

            <!-- Status Message -->
            <?php
            if (!empty($statusMsg)) { ?>
              <div class="alert alert-<?php echo $status; ?>" role="alert">
                <?php echo $statusMsg; ?>
              </div>
            <?php
            } ?>

            <form action="addEvent.php" method="POST">
              <div class="form-group">
                <label>Event Title</label>
                <input type="text" name="title" value="<?php echo !empty($postData['title']) ? $postData['title'] : ''; ?>" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Event Description</label>
                <textarea class="form-control" name="description" rows="3"><?php echo !empty($postData['description']) ? $postData['description'] : ''; ?></textarea>
              </div>
              <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo !empty($postData['location']) ? $postData['location'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>date</label>
                <input type="date" name="date" class="form-control" value="<?php echo !empty($postData['date']) ? $postData['date'] : ''; ?>">
              </div>
              <div class="form-group">
                <label>Time From</label>
                <input type="time" name="time_from" class="form-control" value="<?php echo !empty($postData['time_from']) ? $postData['time_from'] : ''; ?>">
                <span>To</span>
                <input type='time' name='time_to' class="form-control" value="<?php echo !empty($postData['time_to']) ? $postData['time_to'] : ''; ?>">
              </div>
              <div class="form-group mt-3">
                <button type="submit" name="AddEvent" class='btn btn-md btn-block btn-success'>Add Event</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>