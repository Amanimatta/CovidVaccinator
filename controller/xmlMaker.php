<?php
session_start();

if (!isset($_SESSION['userId'])) {

    die(print_r("The page " . $_SERVER['REQUEST_URI'] . " you are trying to reach cannot be reached."));
}
try{
include("dbConnect.php");

// Start XML file, create parent node
$doc = new DOMDocument('1.0','utf-8');
$node = $doc->createElement("markers");
$parnode = $doc->appendChild($node);

//get provider_id from patient_dashboard
$postProviderId=intval($_GET['provider_id']);


//get user_id from dashboard
$patient_id = intval($_GET['patient_id']);
//print_r( $patient_id);
// Select all the rows in the markers table
$query = "SELECT provider_id,provider_name,latitude,longitude,CONCAT(street_number,',',street_name,',',city,',',state,',',zip_code) AS address
          FROM providers
          WHERE
          provider_id = ?;
;";
$params = array($postProviderId);
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqlUser = "SELECT patient_id,first_name,latitude,longitude,CONCAT(street_number,',',street_name,',',city,',',state,',',zip_code) AS address
            FROM patients
            WHERE
            patient_id =".$patient_id.";";

header("Content-type: text/xml");


// Iterate through the rows, adding XML nodes for each
foreach($conn->query($sqlUser) as $patients){
  $node = $doc->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id", $patients['patient_id']);
  $newnode->setAttribute("name", $patients['first_name']);
  $newnode->setAttribute("address", $patients['address']);
  $newnode->setAttribute("lat", $patients['latitude']);
  $newnode->setAttribute("lng", $patients['longitude']);
  $newnode->setAttribute("type", 'Home');
  }
foreach ($providers as $row){
  // Add to XML document node
  $node = $doc->createElement("marker");
  $newnode = $parnode->appendChild($node);

  $newnode->setAttribute("id", $row['provider_id']);
  $newnode->setAttribute("name", $row['provider_name']);
  $newnode->setAttribute("address", $row['address']);
  $newnode->setAttribute("lat", $row['latitude']);
  $newnode->setAttribute("lng", $row['longitude']);
  $newnode->setAttribute("type", 'centre');
}
$_SESSION['xmlParse'] = true;
echo $doc->saveXML();
}
catch(Exception $e){
  die(print_r($e->getMessage()));
}
?>