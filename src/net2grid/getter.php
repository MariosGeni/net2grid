<?php

$url = "https://a831bqiv1d.execute-api.eu-west-1.amazonaws.com/dev/results";
$json = file_get_contents($url);
$decoded_data = json_decode($json);

foreach ($decoded_data as $key => $value){
    echo "$key $value", PHP_EOL;
}

echo $decoded_data->endpointId;