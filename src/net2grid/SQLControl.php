<?php

$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "assignment";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO messages (n_gateway_eui, n_profile, n_endpoint, n_cluster, n_attribute) VALUES (1,1,1,1,1)";

if ($conn->query($sql) === TRUE) {
    echo "New record";
} else {
    echo "Shit Happens";
}

//echo "Connection successfully";

$conn->close();
?>
