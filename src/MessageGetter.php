<?php

namespace App;

class MessageGetter
{

    /**
     * @return mixed
     *
     * Getting a JSON object from an url and converting it from hexadecimal to decimal
     */
    public function getMessage(): mixed
    {
        $url = "https://xqy1konaa2.execute-api.eu-west-1.amazonaws.com/prod/results";
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
     * @param string $hex
     * @return int | string
     *
     *The conversion from hexadecimal to decimal
     */
    public function hex_to_dec(string $hex): int | string
    {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            // This line of code converts each character of hex
            // from hexadecimal to decimal and adds it to $dec
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }
}