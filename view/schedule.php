<?php

include('dbConnect.php');
if(!isset($_SESSION['userId'])){
  die(print_r("<br>The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached.")
);

}
else {

  echo <<<_END

  <script src="validate_functions.js"></script>

    <hr>

    <h3>Weekly Availability</h3>
    <p>Please check which time blocks durring the week you are available</p>

  <table class = "schedule" cellpadding="2" cellspacing="5" align="center">
  <form method="post"  action = "addschedule.php">



  <tr>
      <td class = "schedule-head">Day of the week</td>
      <td class = "schedule-head">Midnight - 4am</td>
      <td class = "schedule-head">4am - 8am</td>
      <td class = "schedule-head">8am - Noon</td>
      <td class = "schedule-head">Noon - 4pm</td>
      <td class = "schedule-head">4pm - 8pm</td>
      <td class = "schedule-head">8pm - Midnight</td>
  </tr>

<tr>
  <td>Monday</td>
  <td><input type="checkbox" name="block[]" value="Monday:1"></td>
  <td><input type="checkbox" name="block[]" value="Monday:2"></td>
  <td><input type="checkbox" name="block[]" value="Monday:3"></td>
  <td><input type="checkbox" name="block[]" value="Monday:4"></td>
  <td><input type="checkbox" name="block[]" value="Monday:5"></td>
  <td><input type="checkbox" name="block[]" value="Monday:6"></td>
</tr>

<tr>
  <td>Tuesday</td>
  <td><input type="checkbox" name="block[]" value="Tuesday:1"></td>
  <td><input type="checkbox" name="block[]" value="Tuesday:2"></td>
  <td><input type="checkbox" name="block[]" value="Tuesday:3"></td>
  <td><input type="checkbox" name="block[]" value="Tuesday:4"></td>
  <td><input type="checkbox" name="block[]" value="Tuesday:5"></td>
  <td><input type="checkbox" name="block[]" value="Tuesday:6"></td>
</tr>

<tr>
  <td>Wednesday</td>
  <td><input type="checkbox" name="block[]" value="Wednesday:1"></td>
  <td><input type="checkbox" name="block[]" value="Wednesday:2"></td>
  <td><input type="checkbox" name="block[]" value="Wednesday:3"></td>
  <td><input type="checkbox" name="block[]" value="Wednesday:4"></td>
  <td><input type="checkbox" name="block[]" value="Wednesday:5"></td>
  <td><input type="checkbox" name="block[]" value="Wednesday:6"></td>
</tr>

<tr>
  <td>Thursday</td>
  <td><input type="checkbox" name="block[]" value="Thursday:1"></td>
  <td><input type="checkbox" name="block[]" value="Thursday:2"></td>
  <td><input type="checkbox" name="block[]" value="Thursday:3"></td>
  <td><input type="checkbox" name="block[]" value="Thursday:4"></td>
  <td><input type="checkbox" name="block[]" value="Thursday:5"></td>
  <td><input type="checkbox" name="block[]" value="Thursday:6"></td>
</tr>

<tr>
  <td>Friday</td>
  <td><input type="checkbox" name="block[]" value="Friday:1"></td>
  <td><input type="checkbox" name="block[]" value="Friday:2"></td>
  <td><input type="checkbox" name="block[]" value="Friday:3"></td>
  <td><input type="checkbox" name="block[]" value="Friday:4"></td>
  <td><input type="checkbox" name="block[]" value="Friday:5"></td>
  <td><input type="checkbox" name="block[]" value="Friday:6"></td>
</tr>

<tr>
  <td>Saturday</td>
  <td><input type="checkbox" name="block[]" value="Saturday:1"></td>
  <td><input type="checkbox" name="block[]" value="Saturday:2"></td>
  <td><input type="checkbox" name="block[]" value="Saturday:3"></td>
  <td><input type="checkbox" name="block[]" value="Saturday:4"></td>
  <td><input type="checkbox" name="block[]" value="Saturday:5"></td>
  <td><input type="checkbox" name="block[]" value="Saturday:6"></td>
</tr>

<tr>
  <td>Sunday</td>
  <td><input type="checkbox" name="block[]" value="Sunday:1"></td>
  <td><input type="checkbox" name="block[]" value="Sunday:2"></td>
  <td><input type="checkbox" name="block[]" value="Sunday:3"></td>
  <td><input type="checkbox" name="block[]" value="Sunday:4"></td>
  <td><input type="checkbox" name="block[]" value="Sunday:5"></td>
  <td><input type="checkbox" name="block[]" value="Sunday:6"></td>
</tr>
</table>





  <br>
  <input button type="submit" value="Save"></button>
  </form>
  <hr>

_END;
}
?>
