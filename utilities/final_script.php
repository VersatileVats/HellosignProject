<?php

    require 'common.php';
    
    $r_id = $_GET['r_id'];
    $p_id = $_GET['p_id'];
    $status = $_GET['status'];
    $refer_to = $_GET['refer_to'];
    $refer_from = $_GET['refer_from'];
    
    $reason_for_denial = $_GET['rod'];
    
    $find_to = "SELECT h_id from hospitals WHERE h_name = '$refer_to'";
    $find_to_query = mysqli_query($connect, $find_to) or die(mysqli_error($connect));
    $res1 = mysqli_fetch_array($find_to_query);
    
    $find_from = "SELECT h_id from hospitals WHERE h_name = '$refer_from'";
    $find_from_query = mysqli_query($connect, $find_from) or die(mysqli_error($connect));
    $res2 =  mysqli_fetch_array($find_from_query);
    
    if($status == "reject") {

        $delete_query = "DELETE FROM referrals WHERE h_id = '$res2[h_id]' && p_id = '$p_id'";
        $query_result = mysqli_query($connect, $delete_query) or die(mysqli_error($connect));
        
        // Updating the status field in r_hospitals table
        $update_query = "UPDATE r_hospitals SET status = '$status', rod = '$reason_for_denial' WHERE r_id = '$r_id'";
        $query_result = mysqli_query($connect, $update_query) or die(mysqli_error($connect));
    
        header("location: ./../referral_stats.php");
    
        
    } elseif ($status == "accept") {
        
        // this function is for sending the automated SMS to the registered patient and informing them that their referral request has been accepted
        // I am using Twilio's servies here.
        function curlCalls($address) {
            $ch = curl_init();

            //Set the URL that you want to GET by using the CURLOPT_URL option.
            curl_setopt($ch, CURLOPT_URL, $address);

            //Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            //Execute the request.
            $data = curl_exec($ch);
        }
        
        function replaceSpace($var) {
           return str_replace(" ","%20",$var);
        }
        
        $query2 = "SELECT name,phone from patients WHERE p_id = '$p_id'";
        $result_query2 = mysqli_query($connect, $query2) or die(mysqli_error($connect));
        $row = mysqli_fetch_array($result_query2);
        
        $name = $row['name'];
        $name = replaceSpace($name);
        $phone = $row['phone'];
        $refer_to = replaceSpace($refer_to);

        // Turned off the function call because I am using the free trail version and I am currently low on balance even after their $50 trial credit
        // So, saving the free credits, but I have provided the screenshots of the same on DEVPOST
        // curlCalls("https://hook.us1.make.com/cnk2wqp593sdkg7iw5h4ihav9vkjpff2?phone=+91".$phone."&name=".$name."&h_name=".$refer_to); 
        
        
        // delete the referral record:
        $delete_query = "DELETE FROM referrals WHERE h_id = '$res2[h_id]' && p_id = '$p_id'";
        $query_result = mysqli_query($connect, $delete_query) or die(mysqli_error($connect));
        
        //change the h_id for that patient in patients record
        $update_query = "UPDATE patients SET h_id= '$res1[h_id]' WHERE p_id = '$p_id';";
        $query_result = mysqli_query($connect, $update_query) or die(mysqli_error($connect));
        
         // Updating the status field in r_hospitals table
        $update_query_1 = "UPDATE r_hospitals SET status = '$status' WHERE r_id = '$r_id'";
        $query_result_1 = mysqli_query($connect, $update_query_1) or die(mysqli_error($connect));
        
        header("location: ./../referral_stats.php");
    }
?>