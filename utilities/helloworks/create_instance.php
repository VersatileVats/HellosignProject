<?php

// Why this file?: Resposible for sending the agreement email whenever a new Patient/Hospital registers on the platform

require_once('vendor/autoload.php');
include './../common.php';

$client = new \GuzzleHttp\Client();

//In the entire HELLOWORKS file, I have used currentTime() so that I can check whether the JWT has expired or not?
$currentTime = time();

// Gathering the token & expires_at that is stored in the db
$query = "SELECT * from helloworks";
$result_query = mysqli_query($connect, $query) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result_query); 
$token = $row['token'];
$expires_at = $row['expires_at'];

$wfTime = $expires_at;

// Opting between the 'workflow & participant' ids & custom field's array for 2 different templates based on roles
$wfid = ($_SESSION['role'] == 'Patient') ? 'e8MQppCO5dkvmoVf' : 'iWHvr52ddOn3Jiuo';
$pid = ($_SESSION['role'] == 'Patient') ? 'participant1_RLkTys' : 'participant1_GXleN1';
$mfArray = ($_SESSION['role'] == 'Patient') ? ['field_CB4kbh','field_xRms03','field_ZskEA9','field_L1ORrp'] : ['field_0m0Qej','field_vlz095','field_pWMOTO','field_4jp3Pn','field_QNRs8g','field_z0hcbe'];

$email = $_SESSION['email'];

// Gathering values for the respective custom fields
if($_SESSION['role'] == 'Patient') {
    $mergeFieldArray = [
        $mfArray[0] => $_SESSION['name'],
        $mfArray[1] => $email,
        $mfArray[2] => $_SESSION['age'],
        $mfArray[3] => $_SESSION['phone']
    ];
} else {
    $mergeFieldArray = [
        $mfArray[0] => $_SESSION['name'],
        $mfArray[1] => $email,
        $mfArray[2] => $_SESSION['icu'],
        $mfArray[3] => $_SESSION['oxygen'],
        $mfArray[4] => $_SESSION['beds'],
        $mfArray[5] => $_SESSION['doctors']
    ];
}

// If token has expired, go and fetch a new one & store the same in db for further use
if($currentTime > $wfTime) {
    echo ("<script>alert('Token Expired!!'); location.href='./jwt.php'</script>");
} else {
    
    // Setting up correct variables for the table & column values in db
    $table = ($_SESSION['role'] == "Hospital") ? 'hospitals' : 'sole_patient'; 
    $id = ($table == 'hospitals') ? 'h_id' : 'id';
    $value = $_SESSION['id'];
    
    // Creating a new workflow instance
    $response = $client->request('POST', 'https://api.helloworks.com/v3/workflow_instances', [
      'form_params' => [
        'language' => 'en-US',
        'document_delivery' => null,
        'document_delivery_type' => 'attachment',
        'notify_when_complete' => null,
        'workflow_id' => $wfid,
        // will handle the callback response with the file 'call.php'
        'callback_url' => 'https://versatilevats.tech/hackfest/utilities/helloworks/call.php',
        // ensuring that the user gets back to the correct place post signing
        'redirect_url' => 'https://versatilevats.tech/hackfest/utilities/helloworks/getWf.php?'.$id."=".$value,
        'participants' => [
            $pid => [
                'type' => 'email',
                'value' => $email,
                'full_name' => $_SESSION['name']
            ]
    
        ],
        'merge_fields' => $mergeFieldArray,
        // setting up some metadata fields which will help the redirect page after signing
        'metadata' => [
            'role' => $_SESSION['role'],
            'id' => $_SESSION['id']
        ]
      ],
    
      'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer '.$token,
        'Content-Type' => 'application/x-www-form-urlencoded',
      ],
    ]);
    
    $newValue = $response->getBody();
    
    // Turning the JSON into a usable PHP object (array)
    $decoded = json_decode($newValue, true);
    $workflowInstance = $decoded["data"]["id"];

    $update_query = "UPDATE $table SET wfInstanceID = '$workflowInstance' WHERE $id = $value";
    $query_result = mysqli_query($connect, $update_query) or die(mysqli_error($connect));
    
    // Informing the user that the signing mail has been sent
    echo ("<script>alert('Email sent. Sign the agreement'); location.href='./../../requireSign.php'</script>");
}