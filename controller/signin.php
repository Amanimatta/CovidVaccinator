<?php

if (isset($_SESSION['userId'])) {
    session_destroy();
}

//If php is not running check if the httpd.conf contains php8_module and Addtype
session_start();
include('dbConnect.php');

//get the form elements
$user_type = $_POST["u-type"];
$password = $_POST["password"];
//get params that need to be added

$params = array($_POST["username"]);

//check if it is from action request

if(isset($_REQUEST['u-type']))
{
    switch( $_REQUEST['u-type'] )
    {
        case 'patients':
            try{

                //SQL statement
                $sql = "SELECT patient_id,username,pwd,first_name FROM patients WHERE username = ?; ";

                //prepare sql statement
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);

                $userDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(count($userDetails) === 1){
                    foreach($userDetails as $row){
                        $vusername = $row['username'];
                        $vpassword = $row['pwd'];
                        $uid = $row['patient_id'];
                        $fname = $row['first_name'];

                    }

                    $isValid = password_verify ( $password , $vpassword );


                        if($isValid)
                        {
                            $_SESSION['userId'] = $uid;
                            $_SESSION['username'] = $vusername;
                            $_SESSION['fname'] = $fname;

                            header("Location: patientDashboard.php");
                        } else {
                            echo "the password is wrong<br>";

                    }


                }
            }
            catch(Exception $e)
            {
                die( print_r( $e->getMessage() ) );
            }

            case 'providers':
                try{

                    //SQL statement
                    $sql = "SELECT provider_id, provider_name,username,pwd FROM providers WHERE username = ?; ";

                    //prepare sql statement
                    $stmt = $conn->prepare($sql);
                    $stmt->execute($params);

                    $providerDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if(count($providerDetails) === 1){
                        foreach($providerDetails as $row){
                            $puid = $row['provider_id'];
                            $providerName = $row['provider_name'];
                            $pusername = $row['username'];
                            $ppassword = $row['pwd'];
                        }
                        /*
                        if($vpassword === $password){
                            header("Location: patientDashboard.php?username=" . $vusername."&&utype=".$user_type);
                        }*/
                        $isValid = password_verify ( $password , $ppassword );


                        if($isValid)
                        {
                            $_SESSION['userId'] = $puid;
                            $_SESSION['providerName'] = $providerName;
                            $_SESSION['username'] = $pusername;
                            //var_dump($_SESSION);
                            header("Location: providerDashboard.php");
                        } else {
                            echo "the password is wrong<br>";

                    }
                        //hashed password check

                    }
                }
                catch(Exception $e)
                {
                    die( print_r( $e->getMessage() ) );
                }


        }
    }
?>
