<?php

namespace App;

class Getter
{

    /**
     * @return mixed
     *
     * Getting a JSON object from a url and converting it from hexadecimal to decimal
     */
    public function getMessage()
    {
        $url = "https://a831bqiv1d.execute-api.eu-west-1.amazonaws.com/dev/results";
        $json = file_get_contents($url);
        $decoded_data = json_decode($json);


        foreach ($decoded_data as $key => $value) {

            if ($key == "gatewayEui") {
                $decoded_data->gatewayEui = $this->hex_to_dec($value);
            }
            if ($key == "profileId") {
                $decoded_data->profileId = $this->hex_to_dec($value);
            }
            if ($key == "endpointId") {
                $decoded_data->endpointId = $this->hex_to_dec($value);
            }
            if ($key == "clusterId") {
                $decoded_data->clusterId = $this->hex_to_dec($value);
            }
            if ($key == "attributeId") {
                $decoded_data->attributeId = $this->hex_to_dec($value);
            }
            if ($key == "timestamp") {
                $datetime = $value / 1000;
                $value = gmdate("d/m/Y h:i:sa", $datetime);
                $decoded_data->timestamp = $value;
            }
        }

        return $decoded_data;
    }

    /**
     * @param $hex
     * @return int|string
     *
     *The conversion from hexadecimal to decimal
     */
    public function hex_to_dec($hex): int|string
    {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }
}