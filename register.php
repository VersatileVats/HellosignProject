<?php
require 'utilities/common.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>ReferMedi | Register</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
</head>

<body class="bg-gradient-primary" style="user-select: none">
    <div class="container">
        <div class="card shadow-lg o-hidden border-0 my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-flex">
                        <div class="flex-grow-1 bg-register-image" style="background-image: url(&quot;assets/img/register_img.jpg&quot;);"></div>
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="text-dark mb-4" style="font-weight: bold">Create an Account!</h4>
                            </div>
                            
                            <form action="utilities/register_script.php" method="post" class="user" novalidate>
                                <div class="mb-3 btn-user" style="border: black 2px solid">
                                    <select onchange="disableIPField()" id="role" name="role" aria-label="Default select example" style="width: 95%;margin-left: 11px;border: none">
                                        <option>Hospital</option>
                                        <option>Patient</option>
                                    </select>
                                </div>
                                
                                <div class="mb-2" id="hospitalName">
                                    <input class="form-control form-control-user" type="text" id="HospitalName" placeholder="Hospital Name" name="h_name" required>
                                </div>
                                
                                <div class="mb-2" id="patientName" style="display: none">
                                    <input class="form-control form-control-user" type="text" id="patientName" placeholder="Patient Name" name="p_name" required>
                                </div>
                                
                                <div class="mb-2"><input class="form-control form-control-user" type="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Email Address" name="h_email" required></div>
                                <div class="row mb-2">
                                    <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="password" id="examplePasswordInput" placeholder="Password" name="password" required></div>
                                    <div class="col-sm-6"><input class="form-control form-control-user" type="password" id="exampleRepeatPasswordInput" placeholder="Repeat Password" name="password_repeat" required></div>
                                </div>
                                
                                <div id="hospitalFields">
                                
                                <div class="row mb-2">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input class="form-control form-control-user" type="number" id="beds" placeholder="Number of beds" name="beds" required min=0>
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control form-control-user" type="number" id="doctors" placeholder="Number of doctors" name="doctors" required min=0>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input class="form-control form-control-user" type="number" id="beds" placeholder="Available ICU" name="icu" required min=0>
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control form-control-user" type="number" id="doctors" placeholder="Oxygen cylinders" name="oxygen" required min=0>
                                    </div>
                                </div>
                                </div>
                                
                                <div id="patientFields" style="display:none">
                                    
                                    <div class="row mb-2">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input class="form-control form-control-user" type="number" id="age" placeholder="Age" name="age" required min=0>
                                        </div>
                                        <div class="col-sm-6">
                                            <input class="form-control form-control-user" type="text" id="phone" placeholder="Phone Number" name="phone" required min=0 maxlength="10">
                                        </div>
                                    </div>
                                </div>
                                
                                <button class="btn btn-primary d-block btn-user w-100" type="submit" style="font-weight: bold">Register Account</button>
                                
                                <hr>
                            </form>
                            
                            <div class="text-center"><a class="small" href="login.php">Already have an account? Login!</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
    
    <script>
        function disableIPField() {
            var role = document.getElementById('role');
            
            if(role.value == "Patient") {
                document.getElementById('hospitalName').style.display = "none";
                document.getElementById('hospitalFields').style.display = "none";
                document.getElementById('patientFields').style.display = "block";
                document.getElementById('patientName').style.display = "block";
            } else if (role.value == "Hospital") {
                document.getElementById('hospitalName').style.display = "block";
                document.getElementById('hospitalFields').style.display = "block";
                document.getElementById('patientFields').style.display = "none";
                document.getElementById('patientName').style.display = "none";
            }
        }
    </script>
    
</body>

</html>