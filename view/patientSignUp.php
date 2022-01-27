<?php

include('dbConnect.php');
$_SESSION['userId']=TRUE;


echo <<<_END

    <script src="validate_functions.js"></script>

    <hr>

    <h3>Patient Signup</h3>

    <table class = "patientsignup" cellpadding="2" cellspacing="5">


    <form method="post"  action = "addPatient.php" autocomplete="on">

  <tr>
    <td>First Name<span id = "req">*</id></td>
    <td><input type="text" name="first_name" id="first_name" maxlength="50" required></td>
  </tr>

  <tr>
    <td>Middle Initial</td>
    <td><input type="text" name="middle_initial" id="middle_initial" size="6" maxlength="1"></td>
  </tr>

  <tr>
    <td>Last Name</td>
    <td><input type="text" name="last_name" id="last_name" maxlength="50"></td>
  </tr>

  <tr><td></td></tr>

  <tr>
    <td>Social Security Number</td>
    <td>
      <input type="text" name = "ssn1" id = "ssn1" size="3" placeholder="###" minlength="3" maxlength="3" pattern="^[0-9]{3}$"> -
      <input type="text" name = "ssn2" id = "ssn2" size="2" placeholder="##" minlength="2" maxlength="2" pattern="^[0-9]{2}$"> -
      <input type="text" name = "ssn3" id = "ssn3" size="4"  placeholder="####" minlength="4" maxlength="4" pattern="^[0-9]{4}$">
    </td>
  </tr>

  <tr>
    <td>Date Of Birth<span id = "req">*</id></td>
      <td>
        <input type="text" name="dobyear" id = "dobyear" size="5" minlength="4" maxlength="4" placeholder="YYYY" pattern="^(19|20)\d{2}$" required> -
        <input type="text" name="dobmonth" id = "dobmonth" size="3" minlength="2" maxlength="2" placeholder="MM"  pattern="^(0[1-9]|1[012])$" required> -
        <input type="text" name="dobday" id = "dobday" size="3" minlength="2" maxlength="2" placeholder="DD"    pattern="^(0[1-9]|[12]\d|3[01])$" required>
      </td>
  </tr>

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


  <tr><td> </td></tr>
  <tr><td>Address</td></tr>

  <tr>
    <td>House Number<span id = "req">*</id></td>
    <td><input type="text" name="street_number" id = "street_number" maxlength="20" required></td>
  <tr>

  <tr>
    <td>Street Name<span id = "req">*</id></td>
    <td><input type="text" name="street_name" id = "street_name" maxlength="250" required></td>
  </tr>

  <tr>
    <td>Unit Number</td>
    <td><input type="text" name="unit_number" id = "unit_number" maxlength="20"></td>
  </tr>


  <tr>
    <td>City<span id = "req">*</id></td>
    <td><input type="text" name="city" id = "city" maxlength="200" required></td>
  </tr>

  <tr>
    <td>State<span id = "req">*</id></td>
    <td>
_END;
      include "state_select.php";
echo <<<_END

    </td>
  </tr>



  <tr>
    <td>Zip Code<span id = "req">*</id></td>
    <td><input type="text" name="zip_code" id = "zip_code" pattern="^[0-9]{5}$" maxlength="5" minlength="5" required></td>
  </tr>


  <tr>
    <td>County<span id = "req">*</id></td>
    <td><input type="text" name="county" id = "county" maxlength="100" required></td>
  </tr>

  <tr>
    <td>Distance Preference (miles)<span id = "req">*</id></td>
    <td><input type="text" name="distance_preference" id = "distance_preference" pattern="^[0-9]{1,4}$" maxlength="4" required></td>
  </tr>

  <tr>
    <td>Username<span id = "req">*</id></td>
    <td><input type="text" name="username" id = "username" maxlength="320" required></td>
  </tr>

  <tr><td>Password (8 to 16 Characters)<span id = "req">*</id></td>
      <td><input type="password" name="pwd" id = "pwd" minlength="8" maxlength="16" required></tr>
  </tr>

  <tr><td>Confirm Password<span id = "req">*</id></td>
  <td><input type="password" name="pwd_check" id = "pwd_check" onkeyup="check_pwd('pwd','pwd_check','message','submit')"  required></td>

  </tr>

  <tr><td><span id = "req">* required field</id></td>  <td><span id = 'message'></span></td><tr>



  </table>

  <br>
  <input button type="submit" value="Sign Up" id="submit" disabled></button>
  </form>
  <hr>

_END;


?>
