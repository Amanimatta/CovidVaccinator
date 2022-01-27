<?php
  session_start();

  if(!isset($_SESSION['userId'])){
    die(print_r("<hr><br>The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached.")
  );

  }
  else {

    include("dbConnect.php");

    $sql_add_apt = "INSERT INTO available_slots (provider_id, slot_date, start_time, end_time) VALUES
    (:providerId, :aptDate, :startTime, :endTime);";

  //TODO fix hard coded provider data ln 17
  $providerId = $_SESSION['userId'];
  $aptDate = htmlspecialchars($_POST['date']);
  $startTime = htmlspecialchars($_POST['st_hr']) . ":" . htmlspecialchars($_POST['st_mn']);
  $endTime = htmlspecialchars($_POST['end_hr']) . ":" . htmlspecialchars($_POST['end_mn']);
  $numAptments = htmlspecialchars($_POST['qty']);

  //echo "provider $providerId aptDate $aptDate startTime $startTime endtime $endTime qty $numAptments";
  try{
    for($x = $numAptments; $x > 0; $x--){
      $stmt = $conn->prepare($sql_add_apt);
      $stmt -> bindParam(':providerId', $providerId);
      $stmt -> bindParam(':aptDate', $aptDate);
      $stmt -> bindParam(':startTime', $startTime);
      $stmt -> bindParam(':endTime', $endTime);
      $didWork = $stmt -> execute();
    }
    if ($didWork){
      echo "Appointment Upload Successful";
    }
    header("refresh:2;../controller/providerDashboard.php");
  }
  catch (Exception $e)
  {
    die(print_r($e->getMessage()));
  }
  }
?>
