<html>

<?php include ("header.php"); ?>

<hr>
<h1>Welcome</h1>
<p>Signup Type
<form id="form1" name="form1" action="/formaction1" method="post" target="_blank">
  <select name="formchoice" onchange="selectChanged(value)">
    <option value="patientSignUp">please select</option>
    <option value="patientSignUp">patient</option>
    <option value="providerSignUp">provider</option>

 </select>
</form>
</p>



<div class="patients" id="patientSignUp" hidden><?php include ("patientSignUp.php"); ?></div>
<div class="provider" id="providerSignUp" hidden><?php include ("providerSignUp.php"); ?></div>



<script>
  function selectChanged(x) {
    //console.log("this: ", x);
    if ( x === "patientSignUp"){
      //console.log("patientSignUp");
      document.getElementById("patientSignUp").hidden = false;
      document.getElementById("providerSignUp").hidden = true;
    } else {
      //console.log("Else or provider signup, x:", x);
      document.getElementById("patientSignUp").hidden = true;
      document.getElementById("providerSignUp").hidden = false;
    }
  }
</script>







</body>
</html>
