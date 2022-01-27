<?php
session_start();

if (!isset($_SESSION['userId'])) {

    die(print_r("The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached."));
}

include("dbConnect.php");
//var_dump($_SESSION);
$providerName = $_SESSION['providerName'];
$username = $_SESSION['username'];
$providerId = $_SESSION['userId'];

//sql code

$sql_add_apt = "INSERT INTO available_slots (provider_id, slot_date, start_time, end_time) VALUES
(:providerId, :aptDate, :startTime, :endTime);";

$sql_get_future_apts = "select slot_date, start_time, end_time from available_slots where provider_id = :providerId and slot_date >= GETDATE();";


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
            <div>Welcome, <?php echo $providerName; ?></div>
            <a class="nav-link active" href="../view/add_time.html">Add Vaccination Apointments</a>
            <a class="nav-link active" href="../controller/logout.php">Logout</a>


        </nav>
    </div>
    <?php
    //sql to retrieve all past, present and future appointments of the provider

    $sqlproviderAppt = "SELECT match_status,slot_date,available_slots.appointment_id,FORMAT(cast(start_time as time), N'hh\:mm') as start_time,FORMAT(cast(end_time as time), N'hh\:mm')as end_time
    FROM available_slots
    LEFT JOIN appointment_matches
    ON
    available_slots.appointment_id=appointment_matches.appointment_id
    WHERE provider_id=? AND slot_date >= GETDATE(); ";



    //execute the above sql

        $params = array($providerId);
        $stmt = $conn->prepare($sqlproviderAppt);
        $stmt->execute($params);
        $userDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($userDetails)===0){
            echo 'You do not have any upcoming appointments. Please add appointments!! ';
        }
        else{?>
        <br>

        <h3>Future Appointments</h3>
        <table class="table">
            <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Appointment ID</th>
            <th scope="col">Slot Date</th>
            <th scope="col">Slot Time</th>
            <th scope="col">Appointment Status</th>
            </tr>
            </thead>
            <tbody>

        <?php
            $cnt = 0;
            foreach($userDetails as $row){
                ?>


                <?php
                $cnt++;
                if($row['match_status'] === NULL){
                    echo "<tr class='null-status'>";
                    $match_stat = 'Not Assigned';
                }
                else if($row['match_status'] === 'accepted'){
                    echo "<tr class='acc-status'>";
                    $match_stat = 'Accepted';
                }
                else if($row['match_status'] === 'declined'){
                    echo "<tr class='dec-status'>";
                    $match_stat = 'Declined';
                }
                else if($row['match_status'] === 'missed'){
                    echo "<tr class='mis-status'>";
                    $match_stat = 'Missed';
                }
                else if($row['match_status'] === 'completed'){
                    echo "<tr class='com-status'>";
                    $match_stat = 'Completed';
                }
                else if($row['match_status'] === 'offered'){
                    echo "<tr class='off-status'>";
                    $match_stat = 'Offered';
                }
                else if($row['match_status'] === 'canceled'){
                    echo "<tr class='can-status'>";
                    $match_stat = 'Canceled';
                }

                ?>
                <th scope="row"><?php echo $cnt;?></th>
                <td><?php echo $row['appointment_id'];?></td>
                <td><?php echo $row['slot_date'];?></td>
                <td><?php echo $row['start_time'];?>-<?php echo $row['end_time'];?></td>
                <td><?php echo $match_stat;?></td>

                <?php
                
            }
                ?>
            </tbody>
        </table>
        <?php }?>

        <?php
    //sql to retrieve all past, present and future appointments of the provider

    $sqlproviderAppt_past = "SELECT match_status,slot_date,available_slots.appointment_id,FORMAT(cast(start_time as time), N'hh\:mm') as start_time,FORMAT(cast(end_time as time), N'hh\:mm')as end_time
    FROM available_slots
    LEFT JOIN appointment_matches
    ON
    available_slots.appointment_id=appointment_matches.appointment_id
    WHERE provider_id=? AND slot_date < GETDATE(); ";



    //execute the above sql

        $params = array($providerId);
        $stmt = $conn->prepare($sqlproviderAppt_past);
        $stmt->execute($params);
        $userDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($userDetails)===0){
            echo 'You have not have past appointments.';
        }
        else{?>
        <br>

        <h3>Past Appointments</h3>
        <table class="table">
            <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Appointment ID</th>
            <th scope="col">Slot Date</th>
            <th scope="col">Slot Time</th>
            <th scope="col">Appointment Status</th>
            <th scope="col"></th>
            </tr>
            </thead>
            <tbody>

        <?php
            $cnt = 0;
            foreach($userDetails as $row){
                ?>


                <?php
                $cnt++;
                if($row['match_status'] === NULL){
                    echo "<tr class='null-status'>";
                    $match_stat = 'Not Assigned';
                }
                else if($row['match_status'] === 'accepted'){
                    echo "<tr class='acc-status'>";
                    $match_stat = 'Accepted';
                }
                else if($row['match_status'] === 'declined'){
                    echo "<tr class='dec-status'>";
                    $match_stat = 'Declined';
                }
                else if($row['match_status'] === 'missed'){
                    echo "<tr class='mis-status'>";
                    $match_stat = 'Missed';
                }
                else if($row['match_status'] === 'completed'){
                    echo "<tr class='com-status'>";
                    $match_stat = 'Completed';
                }
                else if($row['match_status'] === 'offered'){
                    echo "<tr class='off-status'>";
                    $match_stat = 'Offered';
                }
                else if($row['match_status'] === 'canceled'){
                    echo "<tr class='can-status'>";
                    $match_stat = 'Canceled';
                }

                ?>
                <th scope="row"><?php echo $cnt;?></th>
                <td><?php echo $row['appointment_id'];?></td>
                <td><?php echo $row['slot_date'];?></td>
                <td><?php echo $row['start_time'];?>-<?php echo $row['end_time'];?></td>
                <td><?php echo $match_stat;?></td>
                <?php
                if($row['match_status'] === 'accepted'){
                ?>
                <td>
                <form class="form-horizontal" method="POST" action="missedAppointment.php">
                <button type="submit" class="btn btn-danger" id="misBtn" value="<?php echo $row['appointment_id'];?>" name="misBtn">Missed</button>
                </form>
                <form class="form-horizontal" method="POST" action="completedAppointment.php">
                <button type="submit" class="btn btn-success" id="compBtn" value="<?php echo $row['appointment_id'];?> "  name="compBtn">Completed</button>
                </form>
                </td>
                <?php
            }
            }
                ?>
            </tbody>
        </table>
        <?php }?>



</body>

</html>
