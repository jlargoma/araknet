<?php

$URL = 'https://api.helium.io/v1/';
$data = [
    'min_time'=>'2022-02-07T20:14:00Z',
    'max_time'=>'2022-02-16T20:14:00Z',
    ];
$endpoint = '/accounts/138AVnYE7ECQzG42jGEW7mA1Y5g592762TC9XTghQPg8Y1mj5E5/rewards/sum';

$urlSend = $URL . $endpoint;
if (count($data)) {
  $param = [];
  foreach ($data as $k => $d) {
    $param[] = "$k=$d";
  }
  $urlSend .= '?' . implode('&', $param);
}
echo $urlSend.'<br>';
//$data_string = json_encode($data);
$ch = curl_init($urlSend);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7); //Timeout after 7 seconds
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); //  CURLOPT_TIMEOUT => 10,
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
        )
);
//    } else {
//      $url = $this->URL . $endpoint;
//      if (count($data)) {
//        $param = [];
//        foreach ($data as $k => $d) {
//          $param[] = "$k=$d";
//        }
//        $url .= '?' . implode('&', $param);
//      }
//      $url .= $fixParam;
//      $ch = curl_init($url);
//      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
//      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
////        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7); //Timeout after 7 seconds
//      curl_setopt($ch, CURLOPT_TIMEOUT, 10); //  CURLOPT_TIMEOUT => 10,
//      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
//      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//          'Content-Type: application/json',
//      ));
//    }

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$response = \json_decode($result);

var_dump($httpCode);
var_dump($response);
