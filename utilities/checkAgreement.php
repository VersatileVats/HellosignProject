<?php

include 'common.php';

$table = ($_SESSION['role'] == 'Hospital') ? 'hospitals' : 'sole_patient';
$id = ($_SESSION['role'] == 'Hospital') ? 'h_id' : 'id';
$value = $_SESSION['id'];

$search_query = "SELECT signed from $table WHERE $id = '$value'";
$result_search_query = mysqli_query($connect, $search_query) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result_search_query);
$noOfRows = mysqli_num_rows($result_search_query);  

echo $noOfRows;

if(($row['signed'] != 'completed') && $noOfRows != 0) {
    echo ("<script>alert('Agreement Signing is pending. First, complete that'); location.href='./../requireSign.php'</script>"); 
} elseif($_SESSION['role'] == 'Hospital') {
    header("location: ./../index.php"); 
} elseif($_SESSION['role'] == 'Patient') {
    header("location: ./../patient.php");
} else {
    header("location: ./../index.php");
}

?>