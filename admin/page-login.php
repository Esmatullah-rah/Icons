<?php

session_start();

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $useremail = trim($_POST['userEmail']);
        $userpassword = trim($_POST['userPassword']);

        $connect = mysqli_connect("localhost", "root", "", "mydb");

        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT * FROM users WHERE Email = ?";
        $stmt = mysqli_stmt_init($connect);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $useremail);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($userpassword, $user['Password'])) {

                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['user_email'] = $user['Email'];
                $_SESSION['user_role'] = $user['role']; 
                $_SESSION['user_name'] = $user['Name']; 

                if ($user['role'] === 'admin') {
                    header('Location: index2.php');
                    exit();
                } else {
                    $user_id = $user['ID'];
                    $payment_check = mysqli_prepare($connect, "SELECT Exp_date FROM payments WHERE User_id = ?");
                    mysqli_stmt_bind_param($payment_check, "i", $user_id);
                    mysqli_stmt_execute($payment_check);
                    $result = mysqli_stmt_get_result($payment_check);
                    $payment = mysqli_fetch_assoc($result);

                    $now = date('Y-m-d H:i:s');

                    // var_dump($payment);
                    // var_dump(strtotime($payment['Exp_date']));
                    // var_dump(strtotime($now));
                    // die;
                    
                    if ($payment && strtotime($payment['Exp_date']) > strtotime($now)) {
                        // Payment is valid
                        header('Location: ../users/user_index.php');
                        exit();
                    } else {
                        // No payment or expired
                        header('Location: ../users/payments.php');
                        exit();
                    }
                }
            } else {
                var_dump( "Invalid email or password" );
                die;
            }

        } else {
            echo "You did not regester";
        }
                
    }
?>



<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
<?php require "../includes/Head_linkes.php"; ?>



</head>

<body class="bg-dark">


    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index.php">
                        <img class="align-content" src="images/logo.png" alt="">
                    </a>
                </div>
                <div class="login-form">
                    <form method="POST">
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" name="userEmail" required class="form-control" placeholder="Email">
                        </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="userPassword" required class="form-control" placeholder="Password">
                        </div>
                                <div class="checkbox">
                                    <label>
                                <input type="checkbox"> Remember Me
                            </label>
                                   

                                </div>
                                <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
                                <div class="register-link m-t-15 text-center">
                                    <p>Don't have account ? <a href="page-register.php"> Sign Up Here</a></p>
                                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php include "../includes/footer_less.php"; ?>



</body>

</html>
