<?php

//Connection Info
$serverName = "localhost:1433";
$uid = "sa";
$pwd = "Password!09";
$databaseName = "covid4";

//Connect to DB
try
{
$conn = new PDO("dblib:host=$serverName;dbname=$databaseName",
"$uid","$pwd");
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch(Exception $e)
{
die( print_r( $e->getMessage() ) );
}

?>
