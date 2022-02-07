<?php

// Takes raw data from the request
$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);



file_put_contents(dirname(__FILE__).'/test'.time().'.txt',$json);
die;
?>