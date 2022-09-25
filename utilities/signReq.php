<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <script src="https://cdn.hellosign.com/public/js/embedded/v2.10.0/embedded.development.js"></script>
    <title>ReferMedi | Signing</title>
</head>
<body style="height: 100vh; background: #D3D3D3; overflow: hidden">

<?php
include 'common.php';

/* Why this file?: Setting up all the fileds required for the template and then making an API call for the embedded template. I have used embedded template here because I want to explore this section also and it suites my need also. Becuase REFERRAL is a very big part of the app and I wanted to make sure that before sending the request, the hospital can have a final look at it and make some changes if needed. Also, through the embedding template they can also send the REASON FOR REFERRING */

// pre-set template id
$tid = '893132c93048c946006660755ec605ee344702a1';

require_once __DIR__ . "/vendor/autoload.php";

$config = HelloSignSDK\Configuration::getDefaultConfiguration();

// Configure HTTP basic authorization: api_key
$config->setUsername("API_KEY");

$api = new HelloSignSDK\Api\UnclaimedDraftApi($config);

// Initiating the merge values and providing them values
$mergeField1 = new HelloSignSDK\Model\SubCustomField();
$mergeField1->setName("fromName")
    ->setValue($_GET['fromName']);
    
$mergeField2 = new HelloSignSDK\Model\SubCustomField();
$mergeField2->setName("fromEmail")
    ->setValue($_GET['fromEmail']);
    
$mergeField3 = new HelloSignSDK\Model\SubCustomField();
$mergeField3->setName("PName")
    ->setValue($_GET['PName']);
    
$mergeField4 = new HelloSignSDK\Model\SubCustomField();
$mergeField4->setName("PEmail")
    ->setValue($_GET['PEmail']);
    
$mergeField5 = new HelloSignSDK\Model\SubCustomField();
$mergeField5->setName("PAge")
    ->setValue($_GET['PAge']);
    
$mergeField6 = new HelloSignSDK\Model\SubCustomField();
$mergeField6->setName("PDisease")
    ->setValue($_GET['PDisease']);
    
$mergeField7 = new HelloSignSDK\Model\SubCustomField();
$mergeField7->setName("ToName")
    ->setValue($_GET['ToName']);

$mergeField8 = new HelloSignSDK\Model\SubCustomField();
$mergeField8->setName("ToEmail")
    ->setValue($_GET['ToEmail']);

$signer1 = new HelloSignSDK\Model\SubUnclaimedDraftTemplateSigner();
$signer1->setRole("Hospital")
    ->setName("Harshika Vats")
    ->setEmailAddress($_GET['ToEmail']);

// Using metadata to aid the future processing becuase user can sign the document at any time and these data values will come handy then 
$metadata = [
    'r_id' => $_GET['r_id'],
    'p_id' => $_GET['p_id'],
    'refer_from' => $_GET['fromName'],
    'refer_to' => $_GET['ToName']
];

$data = new HelloSignSDK\Model\UnclaimedDraftCreateEmbeddedWithTemplateRequest();
$data->setClientId("502db432648a8958e9b35e7f26603dc2")
    ->setTemplateIds([$tid])
    ->setRequesterEmailAddress('vishalvats2000@gmail.com')
    ->setCustomFields([$mergeField1, $mergeField2, $mergeField3, $mergeField4, $mergeField5, $mergeField6, $mergeField7, $mergeField8])
    ->setSigners([$signer1])
    ->setMetadata([$metadata])
    ->setSigningRedirectUrl("https://versatilevats.tech/hackfest/utilities/test.php?r_id=".$_GET['r_id'])
    ->setTestMode(true);

try {
    $result = $api->unclaimedDraftCreateEmbeddedWithTemplate($data);
    
    $claim_url = $result['unclaimed_draft']['claim_url'];
    $signature_id = $result['unclaimed_draft']['signature_request_id'];
    
} catch (HelloSignSDK\ApiException $e) {
    $error = $e->getResponseObject();
    echo "Exception when calling HelloSign API: "
        . print_r($error->getError());
    $result = $error->getError();
}

?>

</body>

<script>
    // to keep track whether the user has completed the template and sent it or not?
    var skipped = true;

    const client = new HelloSign({
        clientId: '502db432648a8958e9b35e7f26603dc2'
    });

    client.open('<?php echo($claim_url) ?>', {
        skipDomainVerification: true
    });
    
    client.on('close', (data) => {
        // if the user closed the embedded template and hadn't sent it
        if(skipped) {
            alert("You did not clicked on the Send button. The template was not sent Try again...");
            location.href = "./delReferral.php?r_id=<?php echo $_GET['r_id']?>";
        } 
        // if the user has filled everything correctly and clicked Continue
        else {
            alert("Hurray !! The template has been sent");
            location.href = "./updateSignId.php?r_id=<?php echo $_GET['r_id'] ?>&signatureID=<?php echo $signature_id ?>";
        }
    });
    
    // This finish action triggers off, if the user has clicked on the Click/Continue button which means everything was correctly filled
    client.on('finish', (data) => {
        skipped = false;
    });
    
    // I could have just disabled the close button but that will restrict the user from moving from one page to another and also there can be a case in which by mistkae they refrred the patient. So that close button comes handy
    // document.querySelector('.x-hellosign-embedded__modal-close-button').style.display= "none";
    
</script>

</html>