<?php
  include "header.php";
  if(!isset($_SESSION['userId'])){
    die(print_r("<hr><br>The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached.")
  );

  }
  else{

  //var_dump($_SESSION);
  //sql statements
  include('dbConnect.php');

  if ( ! empty( $_GET['loc'] ) ) {
    $location =  $_GET['loc'];
    $coordinates = explode(":", $location);

    $latitude = $coordinates[0];
    $longitude = $coordinates[1];

    //echo "latitude: $latitude  longitude: $longitude";
    $latitude = substr($latitude,0,12);
    $longitude = substr($longitude,0,12);
    //echo "latitude: $latitude  longitude: $longitude";


    $pid = $_SESSION['patient_id'];

    $sql_add_lat_long = "UPDATE patients SET latitude = :latitude, longitude = :longitude WHERE patient_id = :pid;";

    try{
    $stmt = $conn->prepare($sql_add_lat_long);
    $stmt -> bindParam(':latitude', $latitude);
    $stmt -> bindParam(':longitude', $longitude);
    $stmt -> bindParam(':pid', $pid);
    $stmt->execute();
    }
    catch (Exception $e){
      die(print_r($e->getMessage()));
    }

    echo "<hr>";

    echo "Thank you for signing up!<br>";

    include "schedule.php";


}
else
{


  $sql = "INSERT INTO patients (
        first_name
        , middle_initial
        , last_name
        , ssn
        , dob
        , phone
        , email
        , street_number
        , unit_number
        , street_name
        , zip_code
        , city
        , [state]
        , county
        , username
        , pwd
        , group_id
        , distance_preference
        , created_on)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE())";

  $sql_username_check = "SELECT patient_id FROM patients WHERE username = :username;";

try {

      $first_name = htmlspecialchars($_POST['first_name']);
      $middle_initial = htmlspecialchars($_POST['middle_initial']);
      $last_name = htmlspecialchars($_POST['last_name']);
      $ssn = htmlspecialchars($_POST['ssn1']) . htmlspecialchars($_POST['ssn2']) . htmlspecialchars($_POST['ssn3']);
      $dob = htmlspecialchars($_POST['dobyear']) . "-" . htmlspecialchars($_POST['dobmonth']) . "-" . htmlspecialchars($_POST['dobday']);
      $phone = htmlspecialchars($_POST['phone1']) . "-" . htmlspecialchars($_POST['phone2']) . "-". htmlspecialchars($_POST['phone3']);
        if($_POST['phone4']) {
          $phone = $phone . " x". htmlspecialchars($_POST['phone4']);
        }
      $email = htmlspecialchars($_POST['email']);
      $street_number = htmlspecialchars($_POST['street_number']);
      $unit_number = htmlspecialchars($_POST['unit_number']);
      $street_name = htmlspecialchars($_POST['street_name']);
      $zip_code = htmlspecialchars($_POST['zip_code']);
      $city = htmlspecialchars($_POST['city']);
      $state = htmlspecialchars($_POST['state']);
      $county = htmlspecialchars($_POST['county']);
      $username = htmlspecialchars($_POST['username']);
      // Password hashed
      $pwd = password_hash(htmlspecialchars($_POST['pwd']), PASSWORD_DEFAULT);
      $distance_preference = htmlspecialchars($_POST['distance_preference']);
      //priority group assigning algorithm
      $doy = intval(htmlspecialchars($_POST['dobyear']));
      $age = intval(date('Y'))-$doy;
      if($age>=101 && $age<=110){
        $group_id = 1;
      }
      else if($age>=75 && $age<=100){
        $group_id = 2;
      }
      else if($age>=51 && $age<=74){
        $group_id = 3;
      }
      else if($age>=36 && $age<=50){
        $group_id = 4;
      }
      else if($age>=18 && $age<=35){
        $group_id = 5;
      }
      else if($age>=0 && $age<=17){
        $group_id = 6;
      }
      else{
        echo "<hr>";
        echo "Please enter a valid date of birth!!!<br><hr>";
      }

      //check if username exists alread
      $stmt = $conn->prepare($sql_username_check);
      $stmt -> bindParam(':username', $username);
      $test = $stmt -> execute();
      $result = $stmt->fetch();

      //if it exists give user an error
      if($result){

        echo "<hr>";
        echo "That username already exists.  Please click back and try a different one<br><hr>";

        //else insert the new user into patients
      } else {



      $params = array($first_name, $middle_initial, $last_name, $ssn, $dob, $phone, $email, $street_number, $unit_number, $street_name, $zip_code, $city, $state, $county, $username, $pwd, $group_id, $distance_preference);

      //insert the patient
      $stmt = $conn->prepare($sql);
      $stmt->execute($params);



      //get the patient_id
      $stmt = $conn->prepare($sql_username_check);
      $stmt -> bindParam(':username', $username);
      $stmt -> execute();
      $result = $stmt->fetch();
      $_SESSION['patient_id'] = $result[0];
      //var_dump($_SESSION);

      //insert patient preferred contact
      $patient_id = $_SESSION['patient_id'];

      $sql_preferred_contact_add = "INSERT INTO patient_preferred_contact (
            patient_id,
            method
          )
          VALUES (?, ?)";

      $sql_preferred_contact_delete = "DELETE FROM patient_preferred_contact WHERE patient_id = ?";

      if(isset($_POST["contact"]))
      {
        try{
            //delete patients previous schedule
            $params = array($patient_id);
            $stmt = $conn->prepare($sql_preferred_contact_delete);
            $stmt->execute($params);

                $contacts = ($_POST["contact"]);
                //echo "Adding to Patient Availability:<br>";
                // add each new block to patients schedule
                foreach($contacts as $contact){
                    $params = array($patient_id, $contact);
                    $stmt = $conn->prepare($sql_preferred_contact_add);
                    $stmt->execute($params);
                }
            }
            catch (Exception $e)
            {
              die(print_r($e->getMessage()));
            }
      }


      //code

      echo "<script>

      getLocation('$street_number', '$street_name', '$city', '$state', '$zip_code', 'addPatient.php');
    </script>";

      echo "<hr>";

      echo "Thank you for signing up!<br>";

      }
  }
  catch (Exception $e){
    die(print_r($e->getMessage()));
  }
}
}
?>
