<?php

include('dbConnect.php');
$_SESSION['userId']=TRUE;
echo <<<_END

  <script src="validate_functions.js"></script>

  <hr>


  <h3>Provider Signup</h3>

  <table class = "providerSignup" cellpadding="2" cellspacing="5">

  <form method="post"  action = "addProvider.php" autocomplete="on">

  <tr>
    <td>Name<span id = "req">*</id></td>
    <td><input type="text" name="provider_name" id="provider_name" maxlength="250" required></td>
  </tr>


  <tr>
    <td>Type<span id = "req">*</id></td>
    <td><input type="text" name="provider_type" id="provider_type" maxlength="100" required></td>
  </tr>


  <tr>
    <td>Phone<span id = "req">*</id></td>
    <td>
      (<input type="tel" name="pphone1" id = "pphone1" size="3" minlength="3" maxlength="3" pattern="^[0-9]{3}$" required>) -
      <input type="tel" name="pphone2" id = "pphone2" size="3" minlength="3" maxlength="3" pattern="^[0-9]{3}$" required> -
      <input type="tel" name="pphone3" id = "pphone3" size="4" minlength="4" maxlength="4" pattern="^[0-9]{4}$" required>
      x<input type="tel" name="pphone4" id = "pphone4" size="6" minlength="0" maxlength="6" pattern="^[0-9]{0,6}$">
    </td>
  </tr>

  <tr>
    <td>Email<span id = "req">*</id></td>
    <td><input type="email" name="pemail" id = "pemail" maxlength="320" required></td>
  </tr>


  <tr>
    <td></td>
  </tr>

  <tr>
    <td>Address</td>
  </tr>

  <tr>
    <td>Street Number<span id = "req">*</id></td>
    <td><input type="text" name="pstreet_number" id = "pstreet_number" maxlength="20" required></td>
  <tr>

  <tr>
    <td>Street Name<span id = "req">*</id></td>
    <td><input type="text" name="pstreet_name" id = "pstreet_name" maxlength="250" required></td>
  </tr>


  <tr>
    <td>Unit Number</td>
    <td><input type="text" name="punit_number" id = "punit_number" maxlength="20"></td>
  </tr>


  <tr>
    <td>City<span id = "req">*</id></td>
    <td><input type="text" name="pcity" id = "pcity" maxlength="200" required></td>
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
    <td><input type="text" name="pzip_code" id = "pzip_code" maxlength="10" required></td>
  </tr>


  <tr>
    <td>County<span id = "req">*</id></td>
    <td><input type="text" name="pcounty" id = "pcounty" maxlength="100" required></td>
  </tr>

  <tr>
    <td>Username<span id = "req">*</id></td>
    <td><input type="text" name="pusername" id = "pusername" minlength="8" maxlength="320" required></td>
  </tr>

  <tr>
    <td>Password (8 to 16 Characters)<span id = "req">*</id></td>
    <td><input type="password" name="ppwd" id = "ppwd" minlength="8" maxlength="16" required></tr>
  </tr>

  <tr>
    <td>Confirm Password<span id = "req">*</id></td>
    <td><input type="password" name="ppwd_check" id = "ppwd_check" onkeyup="check_pwd('ppwd','ppwd_check','message2','submit2')"  required></td>
  </tr>

  <tr><td><span id = "req">* required field</id></td>  <td><span id = 'message2'></span></td><tr>

  </table>

  <br>
  <input button type="submit" value="Sign Up" id="submit2" disabled></button>
</form>
<hr>
_END;
?>
