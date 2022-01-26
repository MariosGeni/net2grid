<?php

namespace App\net2grid;

use mysqli;

class Message
{

     public function insertIntoDatabase($conn, $gatewayEui, $profileId, $endpointId, $clusterId, $attributeId){

         $servername = "localhost";
         $username = "root";
         $password = "admin";
         $dbname = "assignment";

         $conn = new mysqli($servername, $username, $password, $dbname);

         if ($conn->connect_error) {
             die("Connection failed: " . $conn->connect_error);
         }



         $sql = "INSERT INTO messages (n_gateway_eui, n_profile, n_endpoint, n_cluster, n_attribute) 
                VALUES ($gatewayEui,$profileId,$endpointId,$clusterId,$attributeId)";

        if ($conn->query($sql) === TRUE) {
            echo "New record";
        } else {
            echo "Shit Happens";
        }
    }
}