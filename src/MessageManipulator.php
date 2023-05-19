<?php

namespace App;

class MessageManipulator
{

    /**
     * @param $databaseConnection
     * @param $routingKey
     * @return void
     *
     * Separating the given string with the character '.' and inserting it to the database
     */
    public function insertMessageToDatabase($databaseConnection, $routingKey): void
    {

        $result = explode('.', $routingKey);

        $sql = "INSERT INTO cand_zktu.messages_marios (v_gateway_eui, v_profile, v_endpoint, v_cluster, v_attribute) 
                VALUES ($result[0],$result[1],$result[2],$result[3],$result[4])";

        if ($databaseConnection->query($sql) === TRUE) {
            echo "New record \n";
        } else {
            echo "There was an unexpected error !\n";
        }
    }
}