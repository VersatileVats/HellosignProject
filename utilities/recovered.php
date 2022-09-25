<?php
    require 'common.php';
    
    $p_id = $_GET['p_id'];
    
    $update_status = "UPDATE patients SET critical= 'false' WHERE p_id = '$p_id'";
    $update_status_query = mysqli_query($connect, $update_status) or die(mysqli_error($connect));
    
    header("location: ./../table.php");

?>