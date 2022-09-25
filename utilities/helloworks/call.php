<?php

// Why this file?: Particularly in Helloworks' case, post signing a document the signer don't receives an email containing the pdf copy of the signed document. 
// So, I wanted to have a localized version of all the AGREEMENTS sent to the new entity whether it has been accepted or declined

$new = file_get_contents('php://input');

// Creating a new text file only for debugging purposes becaue there is no logs for checking the callback responses
// It worked great for me and helped a lot in deugging while production. 
$myfile = fopen("callback.txt", "w") or die("Unable to open file!");
fwrite($myfile, "Data: ".$new."\n");

// Converting the JSON object into an PHP array
$new = json_decode($new,true);
fwrite($myfile, "Type is: ".$new['type']."\n");
fclose($myfile);

// This single line was the toughest one in the helloworks section because I was working with bare PHP and wanted to capture the request body and I tried a 
// thousand things. Generally in HELLOSIGN, we can use $_POST['json'] to capture the response of the callback url but in here, I have to capture the request object
$content = file_get_contents('php://input');
$data = json_decode($content, true);

// ensuring that the workflow is stopped. That is signer has signed the document and it is ready to be downloaded
if($data['type'] == 'workflow_stopped') {
    
    include './../common.php';
    require_once('vendor/autoload.php');
    
    // Extracting the token for API calls
    $query = "SELECT token from helloworks";
    $result_query = mysqli_query($connect, $query) or die(mysqli_error($connect));
    $row = mysqli_fetch_array($result_query); 
    $token = $row['token'];

    $client = new \GuzzleHttp\Client();
    
    $response = $client->request('GET', 'https://api.helloworks.com/v3/workflow_instances/'.$data["id"].'/documents/', [
      'headers' => [
        'Authorization' => 'Bearer '.$token,
      ],
    ]);
    
    // Th response of the above API call is a zipped file, so I have to unzip it and save it locally
    $myfile = fopen("convert.zip", "w") or die("Unable to open file!");
    $txt = $response->getBody();
    fwrite($myfile, $txt);
    
    fclose($myfile);  
    
    $role = $data['metadata']['role'];
    $id = $data['metadata'][id];
    
    $zip = new ZipArchive;
    $newName = $id.'.pdf';
    
    $myfile1 = fopen("stats.txt", "w") or die("Unable to open file!");
    
    // Unzipping the file
    if ($zip->open('convert.zip') === TRUE) {
        if($role == 'Hospital') {
            // Checking whether the hospital entity has cicked YES to the agreement's pdf?
            if($data['data']['form_a6ppVC']['field_daCBUe'][0] != 'choice_Q4UWft') {
                fwrite($myfile1, 'Yes of hosiptal\n');
                // Using a proper naming convention so that I can use it afterwards
                $zip->renameName('Hospital - Agreement.pdf',$newName);
                // Setting the destination location and it depends on what the user filled while signing
                $zip->extractTo('./hAgreements/',array($newName));    
            } else {
                // If the Hospital entity has chosen NO  declined the agreements
                $newName = 'h-'.$id.'.pdf';
                fwrite($myfile1, 'No of hosiptal\n');
                fwrite($myfile1, 'Newname is: '.$newName.'\n');
                $zip->renameName('Hospital - Agreement.pdf',$newName);
                $zip->extractTo('./rejectedAgreements/',array($newName));    
            }
        } elseif ($role == 'Patient') {
            if($data['data']['form_qlDCNH']['field_eazfIy'][0] != 'choice_5xK7z3') {
                fwrite($myfile1, 'Yes of patient\n');
                $zip->renameName('Patient - Agreement.pdf',$newName);
                $zip->extractTo('./pAgreements/',array($newName)); 
            } else {
                $newName = 'p-'.$id.'.pdf';
                fwrite($myfile1, 'No of patient\n');
                fwrite($myfile1, 'Newname is: '.$newname);
                $zip->renameName('Patient - Agreement.pdf',$newName);
                $zip->extractTo('./rejectedAgreements/',array($newName)); 
            }
        }
    }
    fwrite($myfile1, 'End of file\n');
    fclose($myfile1);  
    
    $zip->close();
}
?>