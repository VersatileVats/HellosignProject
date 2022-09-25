<?php
require 'utilities/common.php';
if (!isset($_SESSION['role'])) {
    header("location: play.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>ReferMedi | Table</title>
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
                    <li class="nav-item"><a class="nav-link active" href="table.php" style="font-size: 16px"><i style="font-size: 20px" class="fas fa-table"></i><span>Table</span></a></li>
                    <?php 
                        if($_SESSION['role'] == "Hospital") {
                    ?>
                        <li class="nav-item"><a class="nav-link" href="doc.php?h_id=<?php echo $_SESSION['id'] ?>" style="font-size: 16px"><i style="font-size: 20px" class="fas fa-user-md"></i><span>Doctors</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="schedule.php
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
                        
                        <h3 class="text-dark mb-0" style="font-weight: bold">Patient Records</h3>
                        
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small"><?php echo($_SESSION['name']) ?></span><img class="border rounded-circle img-profile" src="https://img.icons8.com/ios-glyphs/30/000000/test-account.png"/></a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal" style="cursor: pointer"><i class="fas fa-microphone fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Voice Commands</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                
                <div class="container-fluid">
                    <p style="text-align: center">Have a detailed description about the patients registered in your hospital. Click the<span class="text-dark"> details</span> button to know more. </p>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">Patient's Info</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                                <table class="table my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>ID</th>
                                            <th>DOA</th>
                                            <th>Email</th>
                                            <th>Category</th>
                                            <th>Age</th>
                                            <th>Status</th>
                                            <th>More</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query = "SELECT * from patients WHERE h_id = '$_SESSION[id]'";
                                            $result_query = mysqli_query($connect, $query) or die(mysqli_error($connect));
                                            
                                            while($row = mysqli_fetch_array($result_query)) {
                                                
                                            $search_referral_query = "SELECT r_id from referrals WHERE h_id = '$_SESSION[id]' && p_id = '$row[p_id]'";
                                            $result_referral_query = mysqli_query($connect, $search_referral_query) or die(mysqli_error($connect));   
                                            $answer = mysqli_fetch_array($result_referral_query);
                                        ?>
                                        <tr>
                                            <?php
                                                if($answer != 0) {
                                            ?>
                                            <td class="p_name"><?php echo("$row[name]")?> <span style="color: red; font-weight: bold">(<img src="https://img.icons8.com/ios-glyphs/15/fa314a/r.png"/>)</span></td>
                                            <?php
                                                } else {
                                            ?>
                                            <td class="p_name"><?php echo("$row[name]")?></td>
                                            <?php
                                                }
                                            ?>
                                            
                                            <td><?php echo("$row[p_id]")?></td>
                                            <td><?php echo("$row[doa]")?></td>
                                            <td><?php echo("$row[email]")?></td>
                                            <td><?php echo("$row[d_category]")?></td>
                                            <td><?php echo("$row[age]")?></td>
                                            <td>
                                                <?php if($row['critical'] == "true") { ?>
                                                <img style="margin-left: 14px" src="https://img.icons8.com/emoji/20/000000/red-circle-emoji.png"/>
                                                <?php } else { ?>
                                                    <img style="margin-left: 14px" src="https://img.icons8.com/emoji/20/000000/green-circle-emoji.png"/>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php $p_id = $row['p_id']; ?>
                                                <a href="profile.php?action=view_patient&id=<?php echo $p_id ?>">
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    <a href="profile.php?action=create_user">
                                        <img style="margin-top:-12px" src="https://img.icons8.com/ios-filled/25/000000/plus.png"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--Modal for voice commands-->
            <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="font-family:Open Sans,Helvetica Neue,Arial,sans-serif;">
                <div class="modal-dialog modal-dialog-centered" style="color: black">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel" style="margin:auto; color:#0DCAF0"><strong>Page Specific commands</strong></h5>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        <div class="modal-body" style="text-align:justify">
                        1. Delete patient {say the patient id} <br> 
                        2. Show details of patient {say the patient id} <br>
                        3. Add reports of patient {say the patient id} <br><br>
                        
                        <span style="color:#0DCAF0">NOTE:</span> Append the correct patient id at the end of each phrase
                        </div>
                    </div>
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
    
    <div class="alan-btn"></div>
    <script type="text/javascript" src="https://studio.alan.app/web/lib/alan_lib.min.js"></script>
    <script>
    var alanBtnInstance = alanBtn({
        key: "ffd76d92a00cd23e5e7e5d5734a8c8012e956eca572e1d8b807a3e2338fdd0dc/stage",
        onCommand: function (commandData) {
        //  if(commandData.command === "searchPatient") {
        //      var elements = document.getElementsByClassName('p_name');
        //      for (let i=0; i<elements.length; i++) {
        //          if(elements[i].innerHTML === commandData.item) {
        //              elements[i].style.border= "blue 3px inset";
        //              alanBtnInstance.callProjectApi("foundOrNot", {value:"Found "+commandData.item}, function (error, result){
        //                  console.log(error);
        //             });
        //          }
        //      } 
        //  } else 
         if(commandData.command === "createPatient") {
             window.location.replace(commandData.item);
         } else if (commandData.command === "dashBoard") {
            window.location.replace(commandData.item);
        } else if (commandData.command === "referral") {
            window.location.replace(commandData.item);
        } else if (commandData.command === "addName") {
            alanBtnInstance.callProjectApi("checkPatientPage", {value:window.location.href}, function (error, result){
                console.log(error);
            });
        } else if (commandData.command === "changeBeds") {
            alanBtnInstance.callProjectApi("checkHospitalPage", {value:window.location.href}, function (error, result){
                console.log(error);
            });
        } else if (commandData.command === "getDetails") {
            window.location.replace('profile.php?action=view_patient&id='+commandData.item);
        } else if (commandData.command === "uploadReports") {
            window.location.replace('profile.php?action=update_record&p_id='+commandData.item);
        } else if (commandData.command === "deletePatient") {
            window.location.replace('utilities/deleteUser.php?p_id='+commandData.item);
        } else if (commandData.command === "endSession") {
            window.location.replace('logout.php');
        }
        },
        rootEl: document.getElementById("alan-btn"),
    });
    </script>
</body>

</html>