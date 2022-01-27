<?php
  include('dbConnect.php');

  if(!isset($_SESSION['userId'])){
    die(print_r("<hr><br>The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached.")
  );

  }
  else{

  echo <<<_END
  <hr>
  <h3>Patient Documents</h3>
  <p>Please add any supporting documents here</p>
  <table class = "doc_uploads" border="0" cellpadding="2" cellspacing="5"  bgcolor="#eeeeee">

  <form method="post"  action = "addDocument.php" enctype="multipart/form-data">

  <tr>
      <td><input type="file" name="fileToUpload" id="fileToUpload" required></td>
  </tr>
  <tr>
      <td>Max 5MB (JPEG, JPG, PNG, PDF)</td>
  </tr>
  <tr><td colspan="2" align="right"><input type="submit" value="Submit" name="submit">
  </tr>
  </form>
  </table>
  <br><br>
  <p>Continue without adding documents <a href='patientAddFinish.php'><button>Skip this page</button></a></p>


  _END;
}
?>
