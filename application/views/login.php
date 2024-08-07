<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Login Siapad</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon">

    <link href="assets/css/login/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/login/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/login/style.css" rel="stylesheet" type="text/css">
</head>

<body class="pb-0">
    <!-- Begin page -->
    <div class="accountbg"></div>

    <div class="wrapper-page account-page-full">

        <div class="card">
            <div class="card-body">

                <div class="text-center">
                    <a href="/login" class="logo"><img src="assets/img/logo.png" height="72" alt="logo"></a>
                </div>

                <div class="p-1">
                    <h4 class="font-18 m-b-5 text-center text-danger">Selamat datang di Siapad</h4>
                    <p class="text-muted text-center">Kota Bandar Lampung</p>

                    <form class="form-horizontal m-t-30" action="<?= base_url('/Login/getAuth') ?>" method="POST">

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                        </div>

                        <div class="form-group">
                            <label for="userpassword">Password</label>
                            <input type="password" class="form-control" id="userpassword" name="userpassword" placeholder="Enter password">
                        </div>

                        <div class="form-group row m-t-20">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Masuk</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div class="m-t-2 text-center">
            <p>Don't have an account ? <a href="pages-register-2.html" class="font-500 text-primary"> Signup now </a> </p>
            <p>2024 © FTF Globalindo</p>
        </div>

    </div>
    <!-- end wrapper-page -->


    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/waves.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

</body>

</html>