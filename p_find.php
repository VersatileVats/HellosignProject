<?php

include 'utilities/common.php';

$value;
$sort_by = $_POST['sort'];

$query = "SELECT h_id from doctors WHERE speciality = '$sort_by'";
$result_query = mysqli_query($connect, $query) or die(mysqli_error($connect));

if(mysqli_num_rows($result_query) == 0) {
    echo "NA";
}
else {
    
    while($row = mysqli_fetch_array($result_query)) {
        
        if($value != $row[0]) {
            $value = $row[0];
        
            $query1 = "SELECT * from hospitals WHERE h_id = '$row[h_id]'" ;    
            $result_query1 = mysqli_query($connect, $query1) or die(mysqli_error($connect));
            $details = mysqli_fetch_array($result_query1);
        
            $query2 = "SELECT d_id from doctors WHERE speciality = '$sort_by' && h_id = '$row[h_id]'";
            $result_query2 = mysqli_query($connect, $query2) or die(mysqli_error($connect));
        
    
            echo(mysqli_num_rows($result_query2)."-");
            echo $details['h_name']."-".$details['h_email']."-".$row['h_id']."-".$sort_by."&";
            
        }
    }
    
}
