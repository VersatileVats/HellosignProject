<!DOCTYPE html>
<html>

<?php 

    include 'utilities/common.php'

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>ReferMedi | Sign Required</title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="index/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">

</head>

<body>
    
    <div class="conatiner h-100 d-flex justify-content-center align-items-center">
        <div class="card" style="width: 350px">
            <div class="card-header bg-warning text-dark text-center" style="font-weight: bold">Attention Required</div>
            <div class="card-body">
                <p style="text-align: justify">Check <b><?php echo $_SESSION['email'] ?></b> and sign the agreement that is shared with you. When done, click the below button</p>
                <div style="text-align: center">
                    <button onclick="location.href = 'utilities/checkAgreement.php'" class="btn btn-secondary">Procced</button>
                    <button onclick="location.href = 'logout.php'" class="btn btn-secondary">Logout</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="index/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="index/js/creative.js"></script>

</body>

</html>