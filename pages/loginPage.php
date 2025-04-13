<?php require_once '../system/config.php';

if (checkUserLogin(false)) {
    header('Location: ' . $site_url . 'index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <!-- Latest compiled JavaScript -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">


    <link rel="stylesheet" href="../css/login.css">
</head>

<body class="d-flex">

    <!-- LOGIN FORM -->
    <form id="loginForm" action="" method="POST" onsubmit="return false;">
        <div class="d-flex justify-content-center">
            <h3>NOTE APP</h3>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="usname_login" placeholder="Username" name="usrname">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="pwd_login" placeholder="Password" name="pwd">
        </div>
        <div class="d-flex justify-content-around">
            <button type="button" class="btn btn-primary" style="width: 40%" onclick="login()">Login</button>
            <button type="button" class="btn btn-secondary" style="width: 40%" data-toggle="modal" data-target="#registerModal">Register</button>
        </div>
    </form>

    <!-- REGISTER MODAL -->
    <div class="modal fade" id="registerModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Modal Heading</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <!-- Add-Note Form -->
                    <form id="registerForm" action="" method="POST" onsubmit="return false;">
                        <div class="form-group">
                            <label for="name_register">Name:</label>
                            <input type="text" class="form-control" id="name_register" placeholder="Enter your name..." name="name">
                        </div>
                        <div class="form-group">
                            <label for="sname_register">Surname:</label>
                            <input type="text" class="form-control" id="sname_register" placeholder="Enter your surnname..." name="sname">
                        </div>
                        <div class="form-group">
                            <label for="usrname_register">Username</label>
                            <input type="text" class="form-control" id="usrname_register" placeholder="Enter your Username..." name="usrname">
                        </div>
                        <div class="form-group">
                            <label for="pwd_register">Password</label>
                            <input type="password" class="form-control" id="pwd_register" placeholder="Enter your password..." name="pwd">
                        </div>
                        <div class="form-group">
                            <label for="pwd2_register">Confirm your password</label>
                            <input type="password" class="form-control" id="pwd2_register" placeholder="Re-enter your password..." name="pwd2">
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="registerBtn" onclick="register()">Register</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="../js/userManager.js"></script>

</body>

</html>