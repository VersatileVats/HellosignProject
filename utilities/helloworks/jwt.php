<?php

// Why this file?: It will be called whenever the JWT token gets expired

require_once('vendor/autoload.php');
include './../common.php';

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.helloworks.com/v3/token/PUBLIC_KEY', [
  'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer PRIVATE_API_KEY',
  ],
]);

$newValue =  $response->getBody();

$decoded = json_decode($newValue, true);
        
// Parameters extracted from the json file
$data =  $decoded["data"];
$expires_at =  $data['expires_at'];
$token = $data['token'];

// Updating the db
$update_query = "UPDATE helloworks SET token = '$token', expires_at = '$expires_at' WHERE ID = 1";
$query_result = mysqli_query($connect, $update_query) or die(mysqli_error($connect));

// checking that which page requested the token and going back to the same
if(isset($_GET['from'])) {
    echo ("<script>alert('Generated a new token. Re-directing to getWF Page'); location.href='./getWf.php'</script>");
} else {  
    echo ("<script>alert('Generated a new token'); location.href='./create_instance.php'</script>");
}