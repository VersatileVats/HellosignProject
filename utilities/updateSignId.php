<?php

include 'common.php';
    
    $r_id = $_GET['r_id'];
    $signatureID = $_GET['signatureID'];
    
    $update_status = "UPDATE r_hospitals SET signatureID= '$signatureID' WHERE r_id = '$r_id'";
    $update_status_query = mysqli_query($connect, $update_status) or die(mysqli_error($connect));
    echo 'R ID Is: '.$r_id;
    echo 'Sign Request Is: '.$signatureID;
    header("location: ./../referral_stats.php");
?>