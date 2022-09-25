<?php

// header("refresh: 30;");

require 'utilities/common.php';
if (!isset($_SESSION['role'])) {
    header("location: play.php");
} 

if($_SESSION['role'] == "Hospital") {
    header("location: index.php");
}

if($_SESSION['role'] == "Doctor") {
    header("location: doc.php");
}

if(!isset($_GET['h_id'])) {
    header("location:patient.php");
}

$h_id = $_GET['h_id'];
$speciality = $_GET['speciality'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>ReferMedi | Doctors</title>
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
                    <li class="nav-item"><a class="nav-link" href="patient.php" style="font-size: 16px"><i style="font-size: 20px" class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="patient_details.php" style="font-size: 16px"><i class="fas fa-user" style="font-size: 20px"></i><span>Profile</span></a></li>
                    <!--<li class="nav-item"><a class="nav-link" href="referral_stats.php" style="font-size: 16px"><i class="fas fa-gear" style="font-size: 20px"></i><span>Stats</span></a></li>-->
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                    
                        <h3 class="text-dark mb-0" style="font-weight: bold">Doctors</h3>
                        
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
                    $search_query = "SELECT * from doctors WHERE h_id = $h_id && speciality = '$speciality'";
                    $result_search_query = mysqli_query($connect, $search_query) or die(mysqli_error($connect)); 
                    
                    $search_query1 = "SELECT d_id, timing,status,signed from appointment WHERE p_id = '$_SESSION[id]' && speciality = '$speciality' ";
                    $result_search_query1 = mysqli_query($connect, $search_query1) or die(mysqli_error($connect));
                    $result = mysqli_fetch_array($result_search_query1) ;
    
                    while($row = mysqli_fetch_array($result_search_query)) {
                ?>
                    <p style="width: 100%; text-align: center">The page refreshes in every 30 seconds</p>
                    <div class="card text-white bg-success m-5 my-2 d-inline-block" style="width: 17rem;">
                    <div class="card-header text-dark">
                        Doctor Id <?php echo $row['d_id'] ?>
                        <?php if($row['d_id'] == $result['d_id']) { ?>
                            <img src="https://img.icons8.com/emoji/25/000000/star-emoji.png" style="float: right"/>
                        <?php } ?>
                    </div>
                    <div class="card-body d-block" style="float:left; width: 65%">
                        <h5 class="card-title">Dr. <?php echo $row['name'] ?></h5>
                        
                        <?php if ($row['speciality'] ==  "Nerves") { ?>
                            <img src="https://img.icons8.com/external-others-pike-picture/25/000000/external-brain-neurology-medicine-others-pike-picture-6.png" id="neuro" />
                        <?php 
                        } elseif ($row['speciality'] == "Bones") { ?>
                            <img src="https://img.icons8.com/external-vitaliy-gorbachev-blue-vitaly-gorbachev/25/000000/external-bones-bad-habits-vitaliy-gorbachev-blue-vitaly-gorbachev.png" id="bones"/>
                        <?php    
                        } elseif ($row['speciality'] == "Eyes") { ?>
                            <img src="https://img.icons8.com/external-creatype-filed-outline-colourcreatype/25/000000/external-eyes-basic-creatype-filed-outline-colourcreatype.png" id="eyes"/>
                        <?php
                        }elseif($row['speciality'] == "Heart") { ?>
                            <img src="https://img.icons8.com/fluency/25/000000/like.png" id="heart"/>
                        <?php
                        } else { ?>
                        <img src="https://img.icons8.com/pastel-glyph/25/000000/throat--v1.png" id="ent"/>
                        <?php    
                        }
                        ?>
                        
                    </div>
                    <div class="card-body mt-2" >
                        <?php
                            if($row['profile'] == "utilities/uploads/avatars/") { ?>
                                <img src="utilities/uploads/user.png" class="img-fluid bg-white" style="border-radius: 50%; height: 60px" alt="Doc">
                            <?php    
                            } else {
                            ?>
                                <img src="<?php echo $row['profile'] ?>" class="img-fluid bg-white" style="border-radius: 50%; height: 60px" alt="Doc">
                            <?php
                            }
                        ?>
                        
                    </div>
                    
                    <div class="card-body">
                        <p class="card-text" style="text-align: center">
                                <button onclick="window.location.href='utilities/appointment.php?d_id=<?php echo $row['d_id'] ?>&h_id=<?php echo $h_id ?>&speciality=<?php echo $speciality ?>&p_id=<?php echo $_SESSION['id'] ?>'" type="button" class="btn btn-outline-dark text-white"
                                    <?php
                                        if(mysqli_num_rows($result_search_query1) != 0) { ?>
                                            disabled
                                            
                                        <?php } ?>
                                    ><b>Appointment</b>
                                </button>
                                
                                <?php if($row['d_id'] == $result['d_id'] and $result['status'] != "" and $result['status'] == "accepted") { ?>
                                    <button onclick = "window.location.href='p_doc.php?speciality=<?php echo $speciality ?>&h_id=<?php echo $h_id ?>&d_id=<?php echo $row['d_id'] ?>'" type="button" class="btn btn-outline-dark text-white">Chat</button>
                                <?php } ?>
                        </p>
                    </div>
                    
                    <div class="card-footer text-center text-dark" style="font-size: 0.8rem">
                        <?php
                            if($row['d_id'] == $result['d_id']) {
                                echo $result['timing'];
                        } else {
                                echo ("Not booked yet");    
                        } ?>
                    </div>
                </div>
                
                <?php
                    }
                ?>
                
                <!--Extra stuff-->
                <?php 
                    if(isset($_GET['d_id'])) {
                        $d_id = $_GET['d_id'];
                 ?>
                    
                    <div class="row my-3" id="after_chat">
                        
                        <div class="col-lg-4">
                            <h3 class="text-dark mb-4" style="text-align: center; border: outset 3px black; border-radius: 10px">Add a prescription</h3>
                            <p >Add or remove upto 5 prescriptions </p>
                            
                            <div class="row">
                                <div class="col">
                                    <div class="card shadow mb-3">
                                        <div class="card-body">
                                        
                                                <div class="row">
                                                    <div class="col">
                                                        <?php
                                                            $query = "SELECT answer,question,p1,p2,p3,p4,p5 from prescription WHERE h_id = '$h_id' && d_id = '$d_id' && p_id = '$_SESSION[id]'";
                                                            $result_query = mysqli_query($connect, $query) or die(mysqli_error    ($connect));
                                                            $result = mysqli_fetch_array($result_query);
                                                            $prescriptions = array($result['p1'],$result['p2'],$result['p3'],$result['p4'],$result['p5']);
                                                            $empty = 0;
                                                            $itr = 1;
                                                            
                                                            foreach ($prescriptions as $value) {
                                                                if($value != "") {
                                                        ?>
                                                                <div class="mb-3 text-dark" style="font-weight: bold">
                                                                <p>
                                                                <?php echo $value ?>
                                                                </p>
                                                                </div> 
                                                                
                                                                
                                                                
                                                        <?php
                                                                $itr ++; } else {$empty ++;}
                                                            } if($empty != 0) {
                                                        ?>
                                                            <div class="small text-center">
                                                            <p><?php echo $empty ?>/5 left</p>
                                                        </div>
                                                        <?php
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <h3 class="text-dark mb-4" style="text-align: center; border: outset 3px black; border-radius: 10px">Doctor's Info</h3>
                            <p>A quick glance of the doctor you are interacting with</p>
                            <div class="table-responsive table mt-2" id="options_available" role="grid" aria-describedby="dataTable_info">
                                   <table class="table my-0">
                                            <thead>
                                                <tr>
                                                <th>Id</th>
                                                <th>Name</th>
                                                <th>Speciality</th>
                                                <th>Phone</th>
                                                </tr>
                                            </thead>
                                            
                                            <?php 
                                                $search_query3 = "SELECT * from doctors WHERE h_id = $h_id && speciality = '$speciality'";
                                                $result_search_query3 = mysqli_query($connect, $search_query3) or die(mysqli_error($connect)); 
                                                $results = mysqli_fetch_array($result_search_query3);
                                            ?>
                                            <tbody>
                                                <td><?php echo $d_id ?></td>
                                                <td><?php echo $results['name'] ?></td>
                                                <td><?php echo $results['speciality'] ?></td>
                                                <td><?php echo $results['phone'] ?></td>
                                            </tbody>
                                        </table> 
                                        <?php
                                            $search_file = "SELECT id,signed from appointment WHERE p_id = '$_SESSION[id]' && speciality = '$speciality' ";
                                            $result_file = mysqli_query($connect, $search_file) or die(mysqli_error($connect));
                                            $result_ans = mysqli_fetch_array($result_file) ;
                                            if($result_ans['signed'] == 'true') {
                                        ?>
                                            <p style="text-align:center; padding-top: 10px"><a href="utilities/gqRecords/<?php echo $result_ans['id'] ?>.pdf" target="_blank">Click to view your signed questionnaire</a></p>
                                        <?php } ?>
                            </div>
                            
                        </div>
                        
                        <div class="col-lg-4">
                            <h3 class="text-dark mb-4" style="text-align: center; border: outset 3px black; border-radius: 10px">Q&A</h3>
                            <p>Auto deletes when patient asks a new question</p>
                            <img src="https://img.icons8.com/stickers/35/000000/q.png"/> 
                            <img src="https://img.icons8.com/stickers/35/000000/u.png"/> 
                            <img src="https://img.icons8.com/stickers/35/000000/e.png"/> 
                            <img src="https://img.icons8.com/stickers/35/000000/s.png"/> 
                            <img src="https://img.icons8.com/stickers/35/000000/t.png"/> 
                            <img src="https://img.icons8.com/stickers/35/000000/i.png"/> 
                            <img src="https://img.icons8.com/stickers/35/000000/o.png"/> 
                            <img src="https://img.icons8.com/stickers/35/000000/n.png"/> 
                            <img id="resetQuestion" src="https://img.icons8.com/fluency/20/000000/recurring-appointment.png" style="float:right; cursor: pointer"/>
                            
                            <input id="submitQuestion" class="my-2 form-control form-control-user" style="border: black 2px solid" type="text" aria-describedby="emailHelp" placeholder="<?php echo $result['question'] ?>" maxlength="50">
                            
                            <script>
                                if(<?php echo $result['question'] != '' ?>) {
                                    document.getElementById('submitQuestion').setAttribute('disabled','');
                                }
                            </script>
                            
                            <img src="https://img.icons8.com/stickers/35/000000/a.png"/>
                            <img src="https://img.icons8.com/stickers/35/000000/n.png"/>
                            <img src="https://img.icons8.com/stickers/35/000000/s.png"/>
                            <img src="https://img.icons8.com/stickers/35/000000/w.png"/>
                            <img src="https://img.icons8.com/stickers/35/000000/e.png"/>
                            <img src="https://img.icons8.com/stickers/35/000000/r.png"/>
                            
                            <input class="my-2 form-control form-control-user" style="border: black 2px solid" type="text" aria-describedby="emailHelp" placeholder="<?php echo $result['answer'] ?>" disabled maxlength="50">
                        </div>
                    </div>
                    <?php } ?>
            </div>
            
            </div>
            
            <!--Modal for voice commands-->
                <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="font-family:Open Sans,Helvetica Neue,Arial,sans-serif;">
                <div class="modal-dialog modal-dialog-centered" style="color: black">
                    <div class="modal-content">
                        
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel" style="margin:auto; color:#0DCAF0"><strong>Page specific voice commands</strong></h5>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        <div class="modal-body" style="text-align:justify">
                        Let's start filling the details <br>
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
    
        <?php 
            if(isset($_GET['d_id'])) {
        ?>  
            <script>
            document.getElementById('resetQuestion').addEventListener("click", async () => {
                let xhr  = new XMLHttpRequest();
                xhr.open("POST","common_chat.php");
                xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xhr.send(`h_id=${<?php echo $h_id ?>}&d_id=${<?php echo $d_id ?>}&action=reset`);
                xhr.onload = function () {location.reload()}; 
            });
            
         document.getElementById('submitQuestion').addEventListener("keyup", async () => {
             if(event.keyCode ===  13) {
                let xhr  = new XMLHttpRequest();
                xhr.open("POST","common_chat.php");
                xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xhr.send(`question=${document.getElementById('submitQuestion').value}&h_id=${<?php echo $h_id ?>}&d_id=${<?php echo $d_id ?>}&action=question`);
                xhr.onload = function () {location.reload()};
             }});
        <?php    }   ?>
    </script>
    
</body>

</html>