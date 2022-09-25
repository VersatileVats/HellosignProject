<?php

//Why this file?: This file is being requested by "notify.php" file and it looks after sending the GENERAL QUESTIONNAIRE (GQ) to the patients' whose appointment gets accepted with one of the doctors. The GQ helps the doctor quickly diagonize the patient's issue by a simple set of choice based questions

// Extracting the data from the GET request
$DName = $_GET['DName'];
$PName = $_GET['PName'];
$email = $_GET['email'];

$id = $_GET['id'];

require_once __DIR__ . "/vendor/autoload.php";
require 'common.php';

$config = HelloSignSDK\Configuration::getDefaultConfiguration();

// Configure HTTP basic authorization: api_key
$config->setUsername("API_KEY");

$api = new HelloSignSDK\Api\SignatureRequestApi($config);

$signer1 = new HelloSignSDK\Model\SubSignatureRequestTemplateSigner();
$signer1->setRole("Patient")
    ->setEmailAddress($email)
    ->setName($PName);

// defining the value for the custom fields
$customField1 = new HelloSignSDK\Model\SubCustomField();
$customField1->setName("PName")
    ->setValue($PName);
    
$customField2 = new HelloSignSDK\Model\SubCustomField();
$customField2->setName("DName")
    ->setValue($DName);
    
$metadata = [
    'id' => $id
];

$data = new HelloSignSDK\Model\SignatureRequestSendWithTemplateRequest();
$data->setTemplateIds(["template_id"])
    ->setSigners([$signer1])
    ->setMetadata($metadata)
    ->setCustomFields([$customField1, $customField2])
    ->setTestMode(true);

try {
    $result = $api->signatureRequestSendWithTemplate($data);
    $signature_id = $result['signature_request']['signature_request_id'];
    
    // Upadting the signature id and storing it in db
    $update_status = "UPDATE appointment SET signatureID= '$signature_id' WHERE id = '$id'";
    $update_status_query = mysqli_query($connect, $update_status) or die(mysqli_error($connect));
    
} catch (HelloSignSDK\ApiException $e) {
    $error = $e->getResponseObject();
    echo "Exception when calling HelloSign API: "
        . print_r($error->getError());
}

header("location: ./../schedule.php");
