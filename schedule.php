<?php
require 'utilities/common.php';
if (!isset($_SESSION['role'])) {
    header("location: play.php");
} 

if($_SESSION['role'] != "Hospital") {
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>ReferMedi | Appointments</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
</head>

<body id="page-top" style="user-select: none">
    <div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon">
                        <img src="https://img.icons8.com/color-glass/48/000000/hospital-2.png" class="bg-white"/>
                    </div>
                    <div class="sidebar-brand-text mx-2"><span>ReferMedi</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link" href="index.php" style="font-size: 16px"><i style="font-size: 20px" class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php?action=hospital" style="font-size: 16px"><i class="fas fa-user" style="font-size: 20px"></i><span>Profile</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="referral_stats.php" style="font-size: 16px"><i class="fas fa-gear" style="font-size: 20px"></i><span>Stats</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="table.php" style="font-size: 16px"><i style="font-size: 20px" class="fas fa-table"></i><span>Table</span></a></li>
                    <?php 
                        if($_SESSION['role'] == "Hospital") {
                    ?>
                        <li class="nav-item"><a class="nav-link" href="doc.php?h_id=<?php echo $_SESSION['id'] ?>" style="font-size: 16px"><i style="font-size: 20px" class="fas fa-user-md"></i><span>Doctors</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="schedule.php
                        " style="font-size: 16px"><i style="font-size: 20px" class="fas fa-list"></i><span>Schedule</span></a></li>
                    <?php  
                        }
                    ?>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                    
                        <h3 class="text-dark mb-0" style="font-weight: bold">Appointments</h3>
                        
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown"><span class="d-none d-lg-inline me-2 text-gray-600"><?php echo($_SESSION['name']) ?></span><img class="border rounded-circle img-profile" src="https://img.icons8.com/ios-glyphs/30/000000/test-account.png"/></a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                        <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            
            <div class="container-fluid">
                
                <?php
                    $search_query = "SELECT * from appointment WHERE h_id = '$_SESSION[id]' ORDER BY timing DESC";
                    $result_search_query = mysqli_query($connect, $search_query) or die(mysqli_error($connect));
                                                 
                    while($result = mysqli_fetch_array($result_search_query)) {
                        $search_query1 = "SELECT name from doctors WHERE d_id = '$result[d_id]'";
                        $result_search_query1 = mysqli_query($connect, $search_query1) or die(mysqli_error($connect));
                        $result1 = mysqli_fetch_array($result_search_query1);
                                                    
                        $search_query2 = "SELECT p_name,email,id from sole_patient WHERE id = '$result[p_id]'";
                        $result_search_query2 = mysqli_query($connect, $search_query2) or die(mysqli_error($connect));
                        $result2 = mysqli_fetch_array($result_search_query2);
                        
                        // $search_query3 = "SELECT status,timing from appointment WHERE p_id = '$result[p_id]' && speciality = '$result[speciality]' && status != ''";
                        $search_query3 = "SELECT status,timing from appointment WHERE p_id = '$result[p_id]' && speciality = '$result[speciality]'";
                        $result_search_query3 = mysqli_query($connect, $search_query3) or die(mysqli_error($connect));
                        $result3 = mysqli_fetch_array($result_search_query3) ;
    
                        
                ?>
                
                <div class="card text-white bg-primary m-5 my-3 d-inline-block" style="width: 16rem;">
                    <div class="card-header text-dark">
                        Doctor Id <?php echo $result['d_id'] ?>
                        <?php if($result3['status'] == "accepted") { ?>
                        <img src="https://img.icons8.com/ios-filled/25/ffffff/circled-a.png" style="float: right; background: green"/>
                        <?php }  elseif($result3['status'] == "rejected")  { ?>
                            <img src="https://img.icons8.com/ios-filled/25/ffffff/circled-r.png" style="float: right; background: green"/>
                        <?php } ?>
                        </div>
                    <div class="card-body">
                        <h5 class="card-title" style="float: left">Dr. <?php echo $result1['name'] ?></h5>
                        
                        <?php if ($result['speciality'] ==  "Nerves") { ?>
                            <img src="https://img.icons8.com/external-others-pike-picture/25/000000/external-brain-neurology-medicine-others-pike-picture-6.png" style="float: right" id="neuro" />
                        <?php 
                        } elseif ($result['speciality'] == "Bones") { ?>
                            <img src="https://img.icons8.com/external-vitaliy-gorbachev-blue-vitaly-gorbachev/25/000000/external-bones-bad-habits-vitaliy-gorbachev-blue-vitaly-gorbachev.png" style="float: right" id="bones"/>
                        <?php    
                        } elseif ($result['speciality'] == "Eyes") { ?>
                            <img src="https://img.icons8.com/external-creatype-filed-outline-colourcreatype/25/000000/external-eyes-basic-creatype-filed-outline-colourcreatype.png" style="float: right" id="eyes"/>
                        <?php
                        }elseif($result['speciality'] == "Heart") { ?>
                            <img src="https://img.icons8.com/fluency/25/000000/like.png" style="float: right" id="heart"/>
                        <?php
                        } else { ?>
                        <img src="https://img.icons8.com/pastel-glyph/25/000000/throat--v1.png" style="float: right" id="ent"/>
                        <?php    
                        }
                        ?>
                        
                    </div>
                    
                    <div class="card-body">
                        <p class="card-text mt-3">
                            <b>Requested by:</b> <?php echo $result2['p_name'] ?> <br>
                            <b>Patient Id:</b> <?php  echo $result['p_id']  ?>
                        </p>
                    </div>
                    
                    <div class="card-body">
                        <p class="card-text" style="text-align: center">
                            <button onclick = "window.location='notify.php?result=accept&doc=<?php echo $result1['name']?>&p_name=<?php echo $result2['p_name'] ?>&email=<?php echo $result2['email'] ?>&id=<?php echo $result['id'] ?>'" type="button" class="btn btn-outline-success text-white"
                                <?php if($result3['status'] != '') { ?>
                                    disabled
                                <?php } ?>
                            ><b>Accept</b></button>
                            <button onclick = "window.location='notify.php?result=reject&id=<?php echo $result['id'] ?>'" type="button" class="btn btn-outline-danger text-white"
                            <?php if($result3['status'] != '') { ?>
                                    disabled
                                <?php } ?>
                            ><b>Reject</b></button>
                        </p>
                    </div>
                    
                    <div class="card-footer text-dark text-center" style="font-size: 0.8rem">
                        <?php echo $result3['timing']; ?>
                    </div>
                </div>
                
                <?php }  ?>
                
            </div>
            
            </div>
            
                
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © ReferMedi 2022</span></div>
                </div>
            </footer>
        </div>
        
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
    
    
</body>

</html>