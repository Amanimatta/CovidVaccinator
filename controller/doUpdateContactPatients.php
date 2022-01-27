<?php
  session_start();

  if(!isset($_SESSION['userId'])){
    die(print_r("<hr><br>The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached.")
  );

  }
  else {
    include("dbConnect.php");

    $sql_patient_update = "UPDATE patients SET phone = :phone, email=:email, distance_preference=:distance WHERE patient_id = :pid;";

    $sql_preferred_contact_add = "INSERT INTO patient_preferred_contact (
      patient_id,
      method
    )
    VALUES (?, ?)";

    $sql_preferred_contact_delete = "DELETE FROM patient_preferred_contact WHERE patient_id = ?";


  $uid = $_SESSION['userId'];

  $phone = htmlspecialchars($_POST['phone1']) . "-" . htmlspecialchars($_POST['phone2']) . "-". htmlspecialchars($_POST['phone3']);
  if($_POST['phone4']) {
    $phone = $phone . " x". htmlspecialchars($_POST['phone4']);
  }
  $email = htmlspecialchars($_POST['email']);
  $distance_preference = htmlspecialchars($_POST['distance_preference']);

  try{
      $stmt = $conn->prepare($sql_patient_update);
      $stmt -> bindParam(':phone', $phone);
      $stmt -> bindParam(':email', $email);
      $stmt -> bindParam(':distance', $distance_preference);
      $stmt -> bindParam(':pid', $uid);
      $didWork = $stmt -> execute();
      if ($didWork){
        try{
          //delete patients previous schedule
          $params = array($uid);
          $stmt = $conn->prepare($sql_preferred_contact_delete);
          $stmt->execute($params);

              $contacts = ($_POST["contact"]);
              //echo "Adding to Patient Availability:<br>";
              // add each new block to patients schedule
              foreach($contacts as $contact){
                  $params = array($uid, $contact);
                  $stmt = $conn->prepare($sql_preferred_contact_add);
                  $stmt->execute($params);
              }
          }
          catch (Exception $e)
          {
            die(print_r($e->getMessage()));
          }
      echo "Contact Update Successful";
      header("refresh:2;../controller/patientDashboard.php");
      }
    }
  catch (Exception $e)
  {
    die(print_r($e->getMessage()));
  }
}
?>
