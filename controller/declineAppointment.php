<<<<<<< HEAD
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

                //decline post
                $postAppointmentId=intval($_POST['declineBtn']);

                //sql to update the appointment_matches if the accepted vaccine appointment is cancelled
                $sqlDeclined = "UPDATE appointment_matches SET match_status='declined' WHERE patient_id=? AND match_status='offered' AND appointment_id=?;";

                //cancel appointment sql execution
                $paramDeclined = array(&$patient_id,&$postAppointmentId);
                $stmtDeclined = $conn->prepare($sqlDeclined);
                $stmtDeclined->execute($paramDeclined);

                header("Location: patientDashboard.php");

                //we should not just display the matched appointments, we should insert them into the appointment_matches table also and
                //before we display the matched appointments we need to check for offered slots in appointment_matches table and add it 
                //with new matches and display them

                //and for accepting matches start a transaction for multi user booking


}
catch(Exception $e){
    die(print_r($e->getMessage()));
}

}
?>
=======
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

                //decline post
                $postAppointmentId=intval($_POST['declineBtn']);

                //sql to update the appointment_matches if the accepted vaccine appointment is cancelled
                $sqlDeclined = "UPDATE appointment_matches SET match_status='declined' WHERE patient_id=? AND match_status='offered' AND appointment_id=?;";

                //cancel appointment sql execution
                $paramDeclined = array(&$patient_id,&$postAppointmentId);
                $stmtDeclined = $conn->prepare($sqlDeclined);
                $stmtDeclined->execute($paramDeclined);

                header("Location: patientDashboard.php");

                //we should not just display the matched appointments, we should insert them into the appointment_matches table also and
                //before we display the matched appointments we need to check for offered slots in appointment_matches table and add it 
                //with new matches and display them

                //and for accepting matches start a transaction for multi user booking


}
catch(Exception $e){
    die(print_r($e->getMessage()));
}

}
?>
>>>>>>> origin/ralph_update
