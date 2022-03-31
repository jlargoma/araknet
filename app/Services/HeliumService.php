<?php

namespace App\Services;

use App\Models\Rates;
use App\Models\User;
use App\Models\Charges;

class HeliumService {

  public $response;
  public $responseCode;

  function send($endpoint, $data = []) {


    $this->response = null;
    $this->responseCode = null;
            
    $URL = 'https://api.helium.io/v1/';

    $urlSend = $URL . $endpoint;
    if (count($data)) {
      $param = [];
      foreach ($data as $k => $d) {
        $param[] = "$k=$d";
      }
      $urlSend .= '?' . implode('&', $param);
    }
    echo $urlSend."\n";
    $ch = curl_init($urlSend);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7); //Timeout after 7 seconds
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); //  CURLOPT_TIMEOUT => 10,
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
            )
    );

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $this->responseCode = $httpCode;

    curl_close($ch);
    if ($httpCode != 200) {
      $this->response = $result;
      return false;
    }

    $this->response = \json_decode($result);

    if (!is_object($this->response) || !$this->response) {
      $this->response = $result;
      return false;
    }

    return TRUE;
  }

  function getHNT_account($start = null, $end = null) {

    if ($start && $end) {
      $data = [
          'min_time' => $start,
          'max_time' => $end
      ];
    } else
      $data = [];

    $endpoint = '/accounts/138AVnYE7ECQzG42jGEW7mA1Y5g592762TC9XTghQPg8Y1mj5E5/rewards/sum';
    return $this->send($endpoint, $data);
  }

  function get_hotspots() {
    $endpoint = '/accounts/138AVnYE7ECQzG42jGEW7mA1Y5g592762TC9XTghQPg8Y1mj5E5/hotspots';
    return $this->send($endpoint);
  }
  function get_hotspot($hotspots) {
    $endpoint = '/hotspots/' . $hotspots;
    return $this->send($endpoint);
  }
  
  function getHNT_hotspots($hotspots, $start = null, $end = null) {
    if (trim($hotspots) == '') {
      $this->response = 'Empty hotspots';
      return false;
    }
    if ($start && $end) {
      $data = [
          'min_time' => $start,
          'max_time' => $end
      ];
    } else
      $data = [];
    $endpoint = '/hotspots/' . $hotspots . '/rewards/sum';
    return $this->send($endpoint, $data);
  }
  
  function getData_accounts() {
    $data = [];
    $endpoint = '/accounts/138AVnYE7ECQzG42jGEW7mA1Y5g592762TC9XTghQPg8Y1mj5E5';
    return $this->send($endpoint, $data);
  }
  
  function getHNT_accounts($start = null, $end = null) {
    if ($start && $end) {
      $data = [
          'min_time' => $start,
          'max_time' => $end
      ];
    } else
      $data = [];
    $endpoint = '/accounts/138AVnYE7ECQzG42jGEW7mA1Y5g592762TC9XTghQPg8Y1mj5E5/rewards/sum';
    return $this->send($endpoint, $data);
  }

}
