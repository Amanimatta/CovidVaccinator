<?php
session_start();
if(!isset($_SESSION['userId'])){
    die(print_r("The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached."));
}
else{
    try{
                include('dbConnect.php');
                //get patient id from previous session
                $patient_id = intval($_SESSION['userId']);
                
                //sql to update the appointment_matches if the accepted vaccine appointment is cancelled
                $sqlCancel = "UPDATE appointment_matches SET match_status='canceled' WHERE patient_id=? AND match_status='accepted';";

                //cancel appointment sql execution
                $paramCancel = array($patient_id);
                $stmtCancel = $conn->prepare($sqlCancel);
                $stmtCancel->execute($paramCancel);

                header("Location: patientDashboard.php");

}
catch(Exception $e){
    die(print_r($e->getMessage()));
}

}
?>
