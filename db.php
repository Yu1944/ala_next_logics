<?php
function connectToDatabase(){
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "ala_next_logistics";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo '<h1 style="font-size: 50px; color: green;"><strong>Connected successfully</strong></h1>';
    return $conn; // Return the PDO connection object
  } catch(PDOException $e) {
    echo '<h1 style="font-size: 50px; color: red;"><strong>Connection failed: </strong></h1>' . $e->getMessage();
    die(); // Terminate script execution on connection failure
  }
}
?>
