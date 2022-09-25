<?php

// Why this file?: This is the callback handler file which will be handling the 2 temmplate callbacks, one for the Referral & other for General Questionnaire

$data = json_decode($_POST['json']);
$api_key = '81e76f0d804bf596ac2bd383aea55a9bbb51bb4330eb800d41d3dcff90cd1113';

// this checks the MD5 header before processing the body of the POST
$md5_header_check = base64_encode(hash_hmac('md5', $data, $api_key));
$md5_header = $_SERVER['Content-MD5'];

if ($md5_header != $md5_header_check) {
     goto nope_skip;
}

// Verify the event hash:
$event_time = $data->event->event_time;
$event_type = $data->event->event_type;
$calculated_hash = hash_hmac("sha256", $event_time . $event_type, $api_key);
$event_hash = $data->event->event_hash;
if ($calculated_hash !== $event_hash) {
     goto nope_skip;
}

// for debugging purposes
$myfile = fopen("callback.txt", "w") or die("Unable to open file!");

$reported_app = $data->event->event_metadata->reported_for_app_id;
// this will work for the Referral Reqeusts as it is using embedded templates and App Callback is there for embedded ones
if ($reported_app === '502db432648a8958e9b35e7f26603dc2') {

     fwrite($myfile, "Callback was initiated by: Referral Reqeust\n"); 
     
     if ($event_type === 'signature_request_all_signed') {
            fwrite($myfile, "Event type is: ".$event_type."\n");
            fwrite($myfile, "Signature ID is: ".$data->signature_request->signature_request_id."\n");
      } elseif ($event_type === 'signature_request_viewed') {
            fwrite($myfile, "Event type is: ".$event_type."\n");\
            fwrite($myfile, "Signature ID is: ".$data->signature_request->signature_request_id."\n");
      } else {
            fwrite($myfile, "Event type is: ".$event_type."\n");
            fwrite($myfile, "Signature ID is: ".$data->signature_request->signature_request_id."\n");
      }
      
} 
// this will work for the general questionarrie because it will be using the Account Callback and it is utmost importance
else {

    // becuase when the signed pdf will be available for doenload, then I will store in locally onto the server as the file_url / Amazon AWS like gets expired after 3 days

    fwrite($myfile, "Event type is: ".$event_type."\n");
    fwrite($myfile, "Signature ID is: ".$data->signature_request->signature_request_id."\n");
    fwrite($myfile, "Subject is: ".$data->signature_request->subject."\n");
    fwrite($myfile, "Metadata ID is: ".$data->signature_request->metadata->id."\n");
    
    fwrite($myfile, "Callback was initiated by:  General Questionnaire\n");
    
    // If the signing stuff is done & the files for downloading are available
    if($event_type == 'signature_request_all_signed') {
        include 'common.php';
        $id = $data->signature_request->metadata->id;
        
        // Running some sql commands
        $find_to = "SELECT signatureID from appointment WHERE id = '$id'";
        $find_to_query = mysqli_query($connect, $find_to) or die(mysqli_error($connect));
        $res = mysqli_fetch_array($find_to_query);
        
        $update_status = "UPDATE appointment SET signed = 'true' WHERE id = '$id'";
        $update_status_query = mysqli_query($connect, $update_status) or die(mysqli_error($connect));
        
        $signID = $res['signatureID'];
        
        require_once __DIR__ . "/vendor/autoload.php";
        
        $config = HelloSignSDK\Configuration::getDefaultConfiguration();
        
        // Configure HTTP basic authorization: api_key
        $config->setUsername("81e76f0d804bf596ac2bd383aea55a9bbb51bb4330eb800d41d3dcff90cd1113");
        
        $api = new HelloSignSDK\Api\SignatureRequestApi($config);
        
        try {
            // figured after a long effort. Saw the entire MODEL directory of Hellosign to see about this particular API call
            // Because apart from the Signature ID, the type and file url parameters are optional. But whe I was not specifying them
            // then there was no file_url being returend whereas in the documentation it says it returns a link from where the doc can be downloaded.

            // Then after many efforts, I declared the 2 optional parameters and then I could get the file_url
            $result = $api->signatureRequestFiles($signID,'pdf','true');
            
            // Stiring the url for curl call
            $url = $result['file_url'];
            $ch = curl_init();   
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
            curl_setopt($ch, CURLOPT_URL, $url);   
            $res = curl_exec($ch);   
            
            // Storing it in the gqRecords directory
            $myfile = fopen('./gqRecords/'.$id.".pdf", "w") or die("Unable to open file!");
            fwrite($myfile, $res);
            
            fclose($myfile);
        
        } catch (HelloSignSDK\ApiException $e) {
            $error = $e->getResponseObject();
            echo "Exception when calling HelloSign API: "
                . print_r($error->getError());
        }   
    }
}

fclose($myfile);
echo 'Hello API Event Received';
nope_skip: