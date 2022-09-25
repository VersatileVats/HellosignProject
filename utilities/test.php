<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <?php

    //Why this page?: This is the redirect_page that will be shown to the user when post signing the document. 

    include 'common.php';
    require_once __DIR__ . "/vendor/autoload.php";
     
    $r_id = $_GET['r_id'];
    
    $query = "SELECT * from r_hospitals WHERE r_id = '$r_id'";
    $result_query = mysqli_query($connect, $query) or die(mysqli_error($connect));
    $row = mysqli_fetch_array($result_query); 
    
    $signatureRequestId = $row['signatureID'];
    
    // $signatureRequestId = $_GET['signatureID'];
    
    $config = HelloSignSDK\Configuration::getDefaultConfiguration();

    // Configure HTTP basic authorization: api_key
    $config->setUsername("81e76f0d804bf596ac2bd383aea55a9bbb51bb4330eb800d41d3dcff90cd1113");   
    
    $api = new HelloSignSDK\Api\SignatureRequestApi($config);

    try {
        $result = $api->signatureRequestGet($signatureRequestId);
        
        // Extracting the metadata values so that I can mark which record from the db is being referred here?
        $r_id = $result['signature_request']['metadata'][0]->r_id;
        $p_id = $result['signature_request']['metadata'][0]->p_id;
        $refer_from =  $result['signature_request']['metadata'][0]->refer_from;
        $refer_to = $result['signature_request']['metadata'][0]->refer_to; 
        
        // Checking whether the hospital has accepted/declined the referral request?
        $status = $result['signature_request']['response_data'][0]["value"];
    
        // If rejected, then extract the reason for denial also
        if($status != "Accept") {
            $rod = $result['signature_request']['response_data'][1]["value"];
        }
        
        // moving the processing to final_script.php file 
        header("location: ./final_script.php?p_id=".$p_id."&r_id=".$r_id."&refer_from=".$refer_from."&refer_to=".$refer_to."&status=".$status."&rod=".$rod);
        
    } catch (HelloSignSDK\ApiException $e) {
        $error = $e->getResponseObject();
        echo "Exception when calling HelloSign API: "
            . print_r($error->getError());
    }
    
   ?>
</body>
</html>