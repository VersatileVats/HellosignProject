<?php

include 'utilities/common.php';

$sort_by = $_POST['real_time'];

$query = "SELECT * from hospitals ORDER BY $sort_by DESC";
$result_query = mysqli_query($connect, $query) or die(mysqli_error($connect));

if(mysqli_num_rows($result_query)==0) {
    echo "NA";
}

while($row = mysqli_fetch_array($result_query)) {
    echo $row['h_id']."*". 
         $row['h_name']."*".
         $row['h_email']."*".
         $row['beds']."*".
         $row['doctor']."*".
         $row['icu']."*".
         $row['oxygen']."&";
}

