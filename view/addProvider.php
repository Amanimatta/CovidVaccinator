<?php
  include "header.php";
  echo "<hr>";
  if(!isset($_SESSION['userId'])){
    die(print_r("<br>The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached.")
  );

  }
  else {



    //sql statements
    include('dbConnect.php');


    //insert lat longitude
    if ( ! empty( $_GET['loc'] ) ) {
      $location =  $_GET['loc'];
      $coordinates = explode(":", $location);

      $latitude = $coordinates[0];
      $longitude = $coordinates[1];

      //echo "latitude: $latitude  longitude: $longitude";
      $latitude = substr($latitude,0,12);
      $longitude = substr($longitude,0,12);
      //echo "latitude: $latitude  longitude: $longitude";


      $pid = $_SESSION["userId"];

      $sql_add_lat_long = "UPDATE providers SET latitude = :latitude, longitude = :longitude WHERE provider_id = :pid;";

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



      echo "Thank you for signing up to be a provider!<br>";

      echo "<br>With your help we can eradicate Covid-19<br>";


      echo "<br>You will be contacted by the administrator";
      echo " once your details have been confirmed";
      echo "<br><br><a href='index.html'><button>Back To Homepage</button></a><br><hr>";



  }
  else
  {



    $sql_username_check = "SELECT provider_id FROM providers WHERE username = :username;";

    $sql = "INSERT INTO providers (provider_name, provider_type, phone, email, street_number, unit_number, street_name, zip_code, city, [state], county, username, pwd, created_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE())";


    $provider_name = htmlspecialchars($_POST['provider_name']);
    $provider_type = htmlspecialchars($_POST['provider_type']);
    $phone = htmlspecialchars($_POST['pphone1']) . "-" . htmlspecialchars($_POST['pphone2']) . "-". htmlspecialchars($_POST['pphone3']);
    if($_POST['pphone4']) {
      $phone = $phone . " x". htmlspecialchars($_POST['pphone4']);
    }



    $email = htmlspecialchars($_POST['pemail']);
    $street_number = htmlspecialchars($_POST['pstreet_number']);
    $unit_number = htmlspecialchars($_POST['punit_number']);
    $street_name = htmlspecialchars($_POST['pstreet_name']);
    $zip_code = htmlspecialchars($_POST['pzip_code']);
    $city = htmlspecialchars($_POST['pcity']);
    $state = htmlspecialchars($_POST['state']);
    $county = htmlspecialchars($_POST['pcounty']);
    $username = htmlspecialchars($_POST['pusername']);
    $pwd = password_hash(htmlspecialchars($_POST['ppwd']), PASSWORD_DEFAULT);






    //check if username is available
    try{
    $stmt = $conn->prepare($sql_username_check);
    $stmt -> bindParam(':username', $username);
    $test = $stmt -> execute();
    $result = $stmt->fetch();
    }
    catch (Exception $e){
      die(print_r($e->getMessage()));
    }


    if($result){


      echo "That username already exists.  Please click back and try a different one<br><hr>";
    } else {






    $params = array($provider_name, $provider_type, $phone, $email, $street_number, $unit_number, $street_name, $zip_code, $city, $state, $county, $username, $pwd);

    //prepare sql statement
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);


    //get the patient_id
    try{
    $stmt = $conn->prepare($sql_username_check);
    $stmt -> bindParam(':username', $username);
    $stmt -> execute();
    $result = $stmt->fetch();
    $_SESSION['userId'] = $result[0];
    }
    catch (Exception $e){
      die(print_r($e->getMessage()));
    }



    echo "<script>

            getLocation('$street_number', '$street_name', '$city', '$state', '$zip_code', 'addProvider.php');
    </script>";



    echo "Thank you for signing up to be a provider!<br>";

    echo "<br>With your help we can eradicate Covid-19<br>";


    echo "<br>You will be contacted by the administrator";
    echo " once your details have been confirmed";
    echo "<br><br><a href='index.html'><button>Back To Homepage</button></a><br><hr>";
    }

  }

}

?>


