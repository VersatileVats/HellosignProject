<?php

// Why this page?: This page is reached from the "profile.php?action=refer_patient" page and it gathers the info that will be sent with the "Referral Request Signing Request"

require 'common.php';

$p_id = $_GET['p_id'];
$refer_to = $_POST['select_hospital'];

$search_patient = "SELECT h_name, h_email from hospitals WHERE h_id = '$_SESSION[id]'";
$patient_search_query = mysqli_query($connect, $search_patient) or die(mysqli_error($connect));
$row = mysqli_fetch_array($patient_search_query);

$refer_from = $row['h_name'];
$refer_from_email = $row['h_email'];

$update_status = "UPDATE patients SET critical= 'true' WHERE p_id = '$p_id'";
$update_status_query = mysqli_query($connect, $update_status) or die(mysqli_error($connect));

$patient = "SELECT * from patients WHERE p_id = '$p_id'";
$patient_query = mysqli_query($connect, $patient) or die(mysqli_error($connect));
$patient_details = mysqli_fetch_array($patient_query);

$insert_query = "INSERT into referrals (h_id,p_id,r_to,r_from) VALUES ('$_SESSION[id]','$p_id','$refer_to','$refer_from')";
$query_result = mysqli_query($connect, $insert_query) or die(mysqli_error($connect));

$insert_query_1 = "INSERT into r_hospitals (h_id,p_id,r_to,r_from) VALUES ('$_SESSION[id]','$p_id','$refer_to','$refer_from')";
$query_result_1 = mysqli_query($connect, $insert_query_1) or die(mysqli_error($connect));
$id = mysqli_insert_id($connect);

$refer_to_email_query = "SELECT h_email from hospitals WHERE h_name = '$refer_to'";
$email_query = mysqli_query($connect, $refer_to_email_query) or die(mysqli_error($connect));
$rows = mysqli_fetch_array($email_query);

$refer_to_email = $rows['h_email'];

// Directing to the signReq.php page that will be making the API call to send the signing mail to the destination
header("location: ./signReq.php?fromName=".$refer_from."&fromEmail=".$refer_from_email."&PName=".$patient_details['name']."&PAge=".$patient_details['age']."&PDisease=".$patient_details['d_category']."&PReason=".$patient_details['note']."&ToName=".$refer_to."&ToEmail=".$refer_to_email."&PEmail=".$patient_details['email']." &r_id=".$id." &p_id=".$p_id); 

?>