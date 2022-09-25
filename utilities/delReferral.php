<?php

include 'common.php';
$r_id = $_GET['r_id'];

$delete_query = "DELETE FROM referrals WHERE r_id = '$r_id'";
$query_result = mysqli_query($connect, $delete_query) or die(mysqli_error($connect));

$delete_query1 = "DELETE FROM r_hospitals WHERE r_id = '$r_id'";
$query_result = mysqli_query($connect, $delete_query1) or die(mysqli_error($connect));

header("location: ./../table.php");

?>