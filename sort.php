<?php
require 'utilities/common.php';

$sort = $_POST['sort'];

$find_own = "SELECT beds, doctor, icu, oxygen from hospitals WHERE h_id = '$_SESSION[id]'";
$find_own_query = mysqli_query($connect, $find_own) or die(mysqli_error($connect));
$row = mysqli_fetch_array($find_own_query);

function sortFunc($x,$y,$z) {
    $better = "SELECT * from hospitals WHERE $x  >'$y' ORDER BY $x DESC";
    $better_query = mysqli_query($z, $better) or die(mysqli_error($z));
    
    if(mysqli_num_rows($better_query) == 0) 
        echo "NA-";
    
    while($rows = mysqli_fetch_array($better_query)) {
        echo $rows['h_name'].": ".$rows[$x]."-";
    }
}

sortFunc($sort,$row[$sort],$connect);

?>