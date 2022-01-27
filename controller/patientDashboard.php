<?php
session_start();
if (!isset($_SESSION['userId'])) {

    die(print_r("The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached."));
}

include("dbConnect.php");
$patientName = '';
try {
    //get patient id from previous session
    $patient_id = intval($_SESSION['userId']);
    $sqlPatient = "SELECT first_name
        FROM patients
        WHERE
        patient_id = ?;";

    $param_pname = array(&$patient_id);
    $stmt_pname = $conn->prepare($sqlPatient);
    $stmt_pname->execute($param_pname);
    $pname = $stmt_pname->fetchAll(PDO::FETCH_ASSOC);
    foreach ($pname as $row) {
        $patientName = $row['first_name'];
    }
} catch (Exception $e) {
    die(print_r($e->getMessage()));
}
?>
<html>

<head>
    <title>Dashboard</title>
    <!-- CSS only -->
    <link href="../view/css/stylePatientDashboard.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

</head>

<body>
    <div>
        <nav class="nav navbar navbar-light bg-light">

            <a class="nav-link active navbar-brand" href="#">
                <img src="../images/logo.png" width="50" height="50" class="d-inline-block align-top" alt="">
            </a>
            <div>Welcome, <?php echo $patientName; ?></div>
            <a class="nav-link active" href="../controller/updateContactPatients.php">Update Contact Information</a>
            <a class="nav-link active" href="../controller/scheduleUpdate.php">Update Available time slot</a>
            <a class="nav-link active" href="../controller/logout.php">Logout</a>



        </nav>
    </div>
    <?php
    /*
    try {
        $sqlLatLong = "SELECT latitude,longitude FROM patients WHERE patient_id=?";

        //execute the above sql query to check if the latitudes and longitudes are null

        $paramsLatLong = array($patient_id);
        $stmtLatLong = $conn->prepare($sqlLatLong);
        $stmtLatLong->execute($paramsLatLong);
        $userLatLong = $stmtLatLong->fetchAll(PDO::FETCH_ASSOC);

        if ($userLatLong['latitude'] === NULL || $userLatLong['longitude'] === NULL) {
            $sqlCalcLoc = "SELECT CONCAT(street_number,', ',street_name,', ',city,', ',state,', ',zip_code) AS addr FROM patients WHERE patient_id=?;";

            //execute the above query
            $paramsCalcLoc = array($patient_id);
            $stmtCalcLoc = $conn->prepare($sqlCalcLoc);
            $stmtCalcLoc->execute($paramsCalcLoc);
            $userCalcLoc = $stmtCalcLoc->fetchAll(PDO::FETCH_ASSOC);

            $address = $userCalcLoc['addr'];
            //get the api key
            $apiKey = 'AIzaSyANGCAjwTU4_oiHdIxkbfhD8beSk7Tec78'; // Google maps now requires an API key.

            $geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?
               address=' . urlencode($address) . '&sensor=false&key=' . $apiKey);

            //print_r($geocode);

            $output = json_decode($geocode);
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;

            //SQL insert location


        }
    } catch (Exception $e) {
        die(print_r($e->getMessage()));
    }
    */
    ?>

    <?php



    try {
        // sql to retrieve all the matching slots for the given patient - this sql works on the basis of FCFS -
        //whenever a patient logs in they can see all the available appointments that match their preference
        //immaterial of the priority group they are in - only priority group is used to check eligibility date
        /*
        $sql =
            "SELECT appointment_id, provider_name,patient_slot_match.slot_date,FORMAT(cast(patient_slot_match.start_time as time), N'hh\:mm') as start_time,FORMAT(cast(patient_slot_match.end_time as time), N'hh\:mm')as end_time
        FROM patients
        LEFT JOIN (
                SELECT patient_id,appointment_id,appointment_slots.provider_id,appointment_slots.slot_date,appointment_slots.start_time,appointment_slots.end_time
                FROM (
                SELECT patients.patient_id,patient_availability.day_of_the_week,blocktime.start_time,blocktime.end_time,priority_group.start_date as eligible_date
                FROM patient_availability
                JOIN blocktime
                ON blocktime.blocktime_id = patient_availability.blocktime_id
                JOIN patients
                ON patients.patient_id = patient_availability.patient_id
                JOIN priority_group
                ON patients.group_id=priority_group.group_id
                WHERE
                patients.patient_id = ? --For a given patient
        )patient_elig_avail,(
                SELECT *,FORMAT(slot_date,'dddd') AS week_day
                FROM available_slots
                WHERE appointment_id
                NOT IN
                (
                    SELECT appointment_matches.appointment_id
                    FROM appointment_matches
                    WHERE match_status IN ('accepted','completed','missed','declined','canceled')
                    )
        )appointment_slots
                WHERE
                patient_elig_avail.day_of_the_week = appointment_slots.week_day
                AND
                appointment_slots.slot_date> patient_elig_avail.eligible_date
                AND
                patient_elig_avail.start_time <= appointment_slots.start_time
                AND
                patient_elig_avail.end_time >= appointment_slots.end_time
                )patient_slot_match
        ON patients.patient_id = pati
        ent_slot_match.patient_id
        LEFT JOIN (SELECT  patients.patient_id,providers.provider_id,provider_name,
                            GEOGRAPHY::Point(patients.latitude, patients.longitude, 4326).STDistance(GEOGRAPHY::Point(providers.latitude, providers.longitude, 4326)) / 1609.344 as Distance
                FROM patients,providers
                WHERE
                patients.patient_id = ?)max_distance
        ON max_distance.provider_id = patient_slot_match.provider_id
        AND
        max_distance.patient_id = patient_slot_match.patient_id
        WHERE
        patients.patient_id = ?
        AND
        max_distance.Distance<= patients.distance_preference --For a given patient
        ORDER BY
        max_distance.Distance ASC;
        ";
        */

        //sql to retrieve all the matching slots for the given patients - this means that all the slots which are accepted, canceled, completed, missed and declined are completely removed from matching, only the ones with offered will reappear in case they need it
        /*
        $sqlInsertOffered = "INSERT INTO appointment_matches(appointment_id,patient_id,match_status,offerDateTime)
        SELECT appointment_id,patients.patient_id,match_status='offered',CURRENT_TIMESTAMP

                FROM patients
                LEFT JOIN (
                        SELECT patient_id,appointment_id,appointment_slots.provider_id,appointment_slots.slot_date,appointment_slots.start_time,appointment_slots.end_time
                        FROM (
                        SELECT patients.patient_id,patient_availability.day_of_the_week,blocktime.start_time,blocktime.end_time,priority_group.start_date as eligible_date
                        FROM patient_availability
                        JOIN blocktime
                        ON blocktime.blocktime_id = patient_availability.blocktime_id
                        JOIN patients
                        ON patients.patient_id = patient_availability.patient_id
                        JOIN priority_group
                        ON patients.group_id=priority_group.group_id
                        WHERE
                        patients.patient_id = ? --For a given patient
                )patient_elig_avail,(
                        SELECT *,FORMAT(slot_date,'dddd') AS week_day
                        FROM available_slots
                        WHERE appointment_id
                        NOT IN
                        (
                            SELECT appointment_matches.appointment_id
                            FROM appointment_matches
                            WHERE match_status IN ('accepted','completed','missed','declined','canceled')
                            )
                )appointment_slots
                        WHERE
                        patient_elig_avail.day_of_the_week = appointment_slots.week_day
                        AND
                        appointment_slots.slot_date> patient_elig_avail.eligible_date
                        AND
                        patient_elig_avail.start_time <= appointment_slots.start_time
                        AND
                        patient_elig_avail.end_time >= appointment_slots.end_time
                        )patient_slot_match
                ON patients.patient_id = patient_slot_match.patient_id
                LEFT JOIN (SELECT  patients.patient_id,providers.provider_id,provider_name,
                                    GEOGRAPHY::Point(patients.latitude, patients.longitude, 4326).STDistance(GEOGRAPHY::Point(providers.latitude, providers.longitude, 4326)) / 1609.344 as Distance
                        FROM patients,providers
                        WHERE
                        patients.patient_id = ?)max_distance
                ON max_distance.provider_id = patient_slot_match.provider_id
                AND
                max_distance.patient_id = patient_slot_match.patient_id
                WHERE
                patients.patient_id = ?
                AND
                appointment_id
		        NOT IN
		        (
			        SELECT appointment_id FROM appointment_matches WHERE patient_id=?
		        )
		        AND
                max_distance.Distance<= patients.distance_preference --For a given patient
                ORDER BY
                max_distance.Distance ASC;";
                    */
        //get patient id from previous session
        $patient_id = intval($_SESSION['userId']);


        //sql to fetch records from Appointment matches periodic procedure- can be found under model folder
        $sql = "SELECT provider_name,providers.provider_id,slot_date,FORMAT(cast(start_time as time), N'hh\:mm') as start_time,FORMAT(cast(end_time as time), N'hh\:mm')as end_time,appointment_matches.appointment_id
        FROM appointment_matches
        JOIN
        available_slots
        ON
        appointment_matches.appointment_id = available_slots.appointment_id
        JOIN
        providers
        ON
        providers.provider_id = available_slots.provider_id
        WHERE
        match_status='offered'
        AND patient_id=?;";

        //sql for completed vaccinations
        $sqlCompleted = "SELECT *
        FROM appointment_matches
        WHERE
        match_status = 'completed'
        AND
        patient_id = ?;";

        //sql for accepted vaccinations
        $sqlAccepted = "SELECT providers.provider_name,providers.provider_id,available_slots.slot_date,FORMAT(cast(available_slots.start_time AS time), N'hh\:mm') AS start_time,FORMAT(cast(available_slots.end_time AS time), N'hh\:mm') AS end_time
        FROM appointment_matches
        JOIN
        available_slots
        ON available_slots.appointment_id = appointment_matches.appointment_id
        JOIN
        providers
        ON available_slots.provider_id = providers.provider_id
        JOIN
        patients
        ON patients.patient_id = appointment_matches.patient_id
        WHERE
        appointment_matches.match_status = 'accepted'
        AND
        patients.patient_id = ?;";

        //those appointments which are not among missed, completed, accepted and if the person declined or cancelled an appointment they will not get that appointment matched
        $params = array($patient_id);
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $userDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //those patients who have already completed their vaccination
        $paramsCompleted = array($patient_id);
        $stmtCompleted = $conn->prepare($sqlCompleted);
        $stmtCompleted->execute($paramsCompleted);
        $userVCompleted = $stmtCompleted->fetchAll(PDO::FETCH_ASSOC);

        //those who have already accepted a vaccination
        $paramsAccepted = array($patient_id);
        $stmtAccepted = $conn->prepare($sqlAccepted);
        $stmtAccepted->execute($paramsAccepted);
        $userVAccepted = $stmtAccepted->fetchAll(PDO::FETCH_ASSOC);

        if (count($userVCompleted) === 1) {
            echo "You have already completed your vaccination";
        } else if (count($userVAccepted) === 1) { ?>
            <div style="margin-left: 20px;">
                <p>Accepted Appointment:</p>
            </div>
            <div class="row">
                <?php

                foreach ($userVAccepted as $row) {
                    $providerName = $row['provider_name'];
                    $slotDate = $row['slot_date'];
                    $startTime = $row['start_time'];
                    $endTime = $row['end_time'];
                    $providerId = $row['provider_id'];
                    ?>

                    <div class="col-sm-3" style="padding-left: 40px;padding-top: 20px;">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Appointment</h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $startTime . " - " . $endTime; ?></h6>
                                <p class="card-text"><?php echo $slotDate; ?></p>
                                <form class="form-horizontal" method="POST" action="https://localhost/covidvaccinator/controller/maps.php?user=<?php echo $patient_id;?>">
                                    <button type="submit" name="mapBtn" class="mapAddress" value="<?php echo $providerId; ?>" ?>Locate on Map</button>
                                </form>
                                <p><?php echo $providerName; ?></p>
                                <button type="button" class="btn btn-danger" onclick="document.location.href='cancelAppointment.php'">Cancel</button>
                            </div>
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div style="margin-left: 20px;">
                    <p>Available Appointments</p>
                </div>
                <div class="row">
                    <?php
                    //display the retrieved records
                    foreach ($userDetails as $row) {
                        $providerName = $row['provider_name'];
                        $slotDate = $row['slot_date'];
                        $startTime = $row['start_time'];
                        $endTime = $row['end_time'];
                        $appointmentId = $row['appointment_id'];
                        $providerId = $row['provider_id'];
                    ?>

                        <div class="col-sm-3" style="padding-left: 40px;padding-top: 20px;">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Appointment</h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $startTime . " - " . $endTime; ?></h6>
                                    <p class="card-text"><?php echo $slotDate; ?></p>
                                    <form class="form-horizontal" method="POST" action="https://localhost/covidvaccinator/controller/maps.php?user=<?php echo $patient_id;?>">
                                    <button type="submit" name="mapBtn" class="mapAddress" value="<?php echo $providerId; ?>" ?>Locate on Map</button>
                                    </form>
                                    <p><?php echo $providerName; ?></p>
                                    <form class="form-horizontal" method="POST" action="acceptAppointment.php">
                                        <button type="submit" class="btn btn-primary" id="acceptBtn" value="<?php echo $appointmentId; ?>" name="acceptBtn">Accept</button>
                                    </form>
                                    <form class="form-horizontal" method="POST" action="declineAppointment.php">
                                    <button type="submit" class="btn btn-danger" id="declineBtn" value="<?php echo $appointmentId; ?>" name="declineBtn">Decline</button>
                                    </form>
                                </div>
                            </div>
                        </div>


                <?php
                    }
                } ?>
                </div>
            <?php
        } catch (Exception $e) {
            die(print_r($e->getMessage()));
        }
            ?>
</body>

</html>
