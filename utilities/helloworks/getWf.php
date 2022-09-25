<?php

// Why this file? :This is the redirect_page which will be seen to the user post signing the workflow ie the Platform Agreement PDF

require_once('vendor/autoload.php');
include './../common.php';

$client = new \GuzzleHttp\Client();

// Extractign the values from db for the authentication purposes
$query = "SELECT * from helloworks";
$result_query = mysqli_query($connect, $query) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result_query); 
$token = $row['token'];
$expires_at = $row['expires_at'];

$wfTime = $expires_at;

// Ensuring that the token is still active becaue the API calls require it for authentication
if($currentTime < $wfTime) {
    // If the token is still active, so gather the correct table and field values for the db
    if(isset($_GET['h_id'])) {
        $value = $_GET['h_id'];
        $table = 'hospitals';
    } else {
        $value = $_GET['id'];
        $table = 'sole_patient';
    }
    
    $id = (isset($_GET['h_id'])) ? 'h_id' : 'id';
    
    // Extracting the instance id from db
    $query = "SELECT wfInstanceID from $table WHERE $id = '$value'";
    $result_query = mysqli_query($connect, $query) or die(mysqli_error($connect));
    $row = mysqli_fetch_array($result_query); 
    $wfID = $row['wfInstanceID'];
    
    $wid = $wfID;
    
    // Using the GET WOrkflow Instance API call to check what options are filled by the signer
    $response = $client->request('GET', 'https://api.helloworks.com/v3/workflow_instances/'.$wid.'?static_keys=false', [
      'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer '.$token,
      ],
    ]);
    
    $values = $response->getBody();

    $decoded = json_decode($values, true);
            
    // Parameters extracted from the json file
    $data =  $decoded["data"];
    
    // Using the metadata values so that I can ensure that I am looking for the correct record in the application
    $value = $data['metadata']['id'];
    $role = $data['metadata']['role'];
    
    $id = ($role == 'Hospital') ? 'h_id' : 'id';

    // So the idea behind the Agreement PDF is that the signer can have a good overview of all our policies (terms & conditions)
    // and if he/she denies the same then I will delete his/her account from the server and he/she will proceed only if he agrees

    if($role == 'Hospital') {
        // if user has declined the agreement
        if($data['data']['form_a6ppVC']['field_daCBUe'][0] === 'choice_Q4UWft') {
            $delete_query = "DELETE FROM $table WHERE $id = '$value'";
            $query_result = mysqli_query($connect, $delete_query) or die(mysqli_error($connect));
            echo ("<script>alert('Deleting the hospital\'s account.'); location.href='./../../logout.php'</script>");
        } else {
            // The user has agreed to the agreements and thus update the SIGNED field in the table
            $update_query = "UPDATE $table SET signed = 'completed' WHERE $id = $value";
            $query_result = mysqli_query($connect, $update_query) or die(mysqli_error($connect));
        }
    } else {
        if($data['data']['form_qlDCNH']['field_eazfIy'][0] === 'choice_5xK7z3') {
            $delete_query = "DELETE FROM $table WHERE $id = '$value'";
            $query_result = mysqli_query($connect, $delete_query) or die(mysqli_error($connect));
            echo ("<script>alert('Deleting the patient\'s account.'); location.href='./../../logout.php'</script>");
        } else {
            // update the SIGNED field in the table
            $update_query = "UPDATE $table SET signed = 'completed' WHERE $id = $value";
            $query_result = mysqli_query($connect, $update_query) or die(mysqli_error($connect));
        }
    }
    
    // directing both entities (hospital and patients) to the correct pages 
    if($role == 'Hospital') {
        echo ("<script>alert('Thanks for accepting the agreements.'); location.href='./../../index.php'</script>");
    } else {
        echo ("<script>alert('Thanks for accepting the agreements.'); location.href='./../../patient.php'</script>");
    }
} 
// Token has expired , so head back to JWT.PHP and generate a new token
else {
    echo ("<script>alert('Token Expired!! Generating a new one'); location.href='./jwt.php?from=getWf'</script>");
}