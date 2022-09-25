<?php

require 'common.php';

$p_id = $_GET['p_id'];
$d_id = $_GET['d_id'];

$delete_query = "DELETE FROM appointment WHERE p_id = '$p_id' && d_id = '$d_id'";
$query_result = mysqli_query($connect, $delete_query) or die(mysqli_error($connect));

header("location: ./../doctor.php");
?>