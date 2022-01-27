<?php
session_start();

if (!isset($_SESSION['userId'])) {

  die(print_r("The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached."));
}
try {
  include("dbConnect.php");
  $sqlPatient = "SELECT * FROM patients WHERE patient_id=?";
  $param_pname = array(&$patient_id);
  $stmt_pname = $conn->prepare($sqlPatient);
  $stmt_pname->execute($param_pname);
  $pname = $stmt_pname->fetchAll(PDO::FETCH_ASSOC);
  foreach ($pname as $row) {

    $phnum = $row['phone'];
    $email = $row['email'];
  }

?>
  <html>

  <head>
    <title>Update Contact</title>
    <!-- CSS only -->
    <link href = "style.css" rel = "stylesheet">

  </head>

  <body>


  <form method="post"  action = "doUpdateContactPatients.php" autocomplete="on">

  <table>
  <tr>
    <td>Phone<span id = "req">*</id></td>
    <td>
      (<input type="tel" name="phone1" id = "phone1" size="3" minlength="3" maxlength="3" pattern="^[0-9]{3}$" required>) -
      <input type="tel" name="phone2" id = "phone2" size="3" minlength="3" maxlength="3" pattern="^[0-9]{3}$" required> -
      <input type="tel" name="phone3" id = "phone3" size="4" minlength="4" maxlength="4" pattern="^[0-9]{4}$" required>
      x<input type="tel" name="phone4" id = "phone4" size="6" minlength="0" maxlength="6" pattern="^[0-9]{0,6}$">
    </td>
  </tr>

  <tr>
    <td>Email<span id = "req">*</id></td>
    <td><input type="email" name="email" id = "email" maxlength="320" required></td>
  </tr>

  <tr>
    <td>Preferred Contact Method<span id = "req">*</id></td>
    <td>
      email <input type="checkbox" name="contact[]" value="e-mail" checked>
          phone <input type="checkbox" name="contact[]" value="phone">
          sms <input type="checkbox" name="contact[]" value="sms">
    </td>
  </tr>
  <tr>
    <td>Distance Preference (miles)<span id = "req">*</id></td>
    <td><input type="text" name="distance_preference" id = "distance_preference" pattern="^[0-9]{1,4}$" maxlength="4" required></td>
  </tr>
  </table>

  <input button type="submit" value="Submit"></button>
</form>
  </body>

  </html>

<?php

} catch (Exception $e) {
  die(print_r($e->getMessage()));
}
?>
