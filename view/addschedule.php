<?php

  include "header.php";
  if(!isset($_SESSION['userId'])){
    die(print_r("<hr><br>The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached.")
  );

  }
  else{
        $patient_id = $_SESSION['patient_id'];
        //var_dump($_SESSION);

        // SQL STATMENTS
        $sql_add = "INSERT INTO patient_availability (
            patient_id,
            day_of_the_week,
            blocktime_id
          )
          VALUES (?, ?, ?)";

        $sql_dlt = "DELETE FROM patient_availability WHERE patient_id = ?";


      // CODE
      echo "<hr>";

      include('dbConnect.php');



      if(isset($_POST["block"]))
      {
        try{
            //delete patients previous schedule
            $params = array($patient_id);
            $stmt = $conn->prepare($sql_dlt);
            $stmt->execute($params);

                $blocks = ($_POST["block"]);
                //echo "Adding to Patient Availability:<br>";
                // add each new block to patients schedule
                foreach($blocks as $block){
                    $time = explode(":", $block);
                    //echo "$time[0] $time[1]<br>";


                    $params = array($patient_id, $time[0], $time[1]);
                    $stmt = $conn->prepare($sql_add);
                    $stmt->execute($params);
                }

            echo "Thank you for adding your availability<br>";
            include "documentUpload.php";
            }
            catch (Exception $e)
            {
              die(print_r($e->getMessage()));
            }
      }

      echo "<hr>";
  }
?>
