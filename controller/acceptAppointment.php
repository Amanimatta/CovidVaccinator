<?php
session_start();
if (!isset($_SESSION['userId'])) {
    die(print_r("The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached."));
} else {
    try {
        include('dbConnect.php');
        //get patient id from previous session
        $patient_id = intval($_SESSION['userId']);

        $postAppointmentId=intval($_POST['acceptBtn']);

        //transaction to avoid simultaneous accesses

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();
        $sqlAccepted = "UPDATE appointment_matches SET match_status='accepted',responseDateTime=CURRENT_TIMESTAMP,
        providerNotified=CURRENT_TIMESTAMP WHERE patient_id=? AND match_status='offered' AND 
        appointment_id=?;";
        //accepted appointment sql execution
        $paramAccepted = array(&$patient_id,&$postAppointmentId);
        $stmtAccepted = $conn->prepare($sqlAccepted);
        $stmtAccepted->execute($paramAccepted);

        if ($stmtAccepted->rowCount() === 0) {
            $conn->rollBack();
            echo '<script>alert("The appointment you have selected has just been booked by another user! 
            Please try another appointment.")</script>';
            header("Location: patientDashboard.php");
        } else {
            $conn->commit();
?>
            <html>

            <head>
                <title>Appointment Confirmation</title>
                <!-- CSS only 
                <link href="../view/css/stylePatientDashboard.css" rel="stylesheet">-->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
            </head>
            <body>
                <div>
                    <p>
                        You have successfully booked your appointment!!!
                        <a href="patientDashboard.php">Go to Dashboard</a>
                        <a href="logout.php">Logout</a>
                    </p>
                </div>
            </body>
            </html>

<?php

        }
    } catch (Exception $e) {
        die(print_r($e->getMessage()));
    }
}

?>