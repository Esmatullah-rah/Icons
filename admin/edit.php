<?php
session_start();
$connect = mysqli_connect("localhost", "root", "", "mydb"); 

if(isset($_GET['edit_id'])){
    $editID = isset($_GET['edit_id'])? (int)$_GET['edit_id'] :0;
    $Query = "SELECT * FROM users WHERE ID = $editID";
    $resut =mysqli_query($connect, $Query);
    $rows = mysqli_fetch_assoc($resut);

}

if (isset($_POST['change'])){
        $up_name = $_POST['up_username'];
        $up_email = $_POST['up_email'];
        $up_role = strtolower($_POST['up_role']);
        $query = "UPDATE users SET Name = '$up_name', Email = '$up_email', role = '$up_role' where ID = $editID";
        $rslt = mysqli_prepare($connect, $query);
        if(mysqli_stmt_execute($rslt)){
            header("Location: tables-basic.php");die;
        }else{
            echo "something is wrong";die;
        }
    }


?>

<!doctype html>

<html class="no-js" lang="en">
<!--<![endif]-->

<head>
<?php require "../includes/Head_linkes.php"; ?>


</head>

<body>
    <!-- Left Panel -->

    <?php include "../includes/sitbar.php"; ?>


    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <?php include "../includes/header.php"; ?>

        <!-- Header-->

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Forms</a></li>
                            <li class="active">Basic</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">


                <div class="row">
                    
                    <!--/.col-->

                    <div class="col-lg-12 d-flex text-center justify-content-center">
                        <div class="card col-md-8">
                            <div class="col-lg-12  p-5 ">
                                <div class="card ">
                                    <div class="card-header">Edit Form</div>
                                    <div class="card-body card-block ">
                                        <form  method="post" >
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                                    <input type="text" id="username" value="<?= $rows['Name']; ?> " name="up_username"  class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                                                    <input type="email" id="email" value="<?= $rows['Email'] ?>"  name="up_email"  class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class="fa fa-bolt "></i></div>
                                                    <input type="text"  value="<?= $rows['role'] ?>" name="up_role"  class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-actions form-group"><button name="change" type="submit" class="btn btn-success btn-lg">Change</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            
                            <script src="../vendors/jquery/dist/jquery.min.js"></script>
                            <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>

                            <script src="../vendors/jquery-validation/dist/jquery.validate.min.js"></script>
                            <script src="../vendors/jquery-validation-unobtrusive/dist/jquery.validate.unobtrusive.min.js"></script>

                            <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
                            <script src="../assets/js/main.js"></script>
</body>
</html>
