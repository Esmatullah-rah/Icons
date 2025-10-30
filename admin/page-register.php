<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = htmlspecialchars(trim(strtolower($_POST['UserFullname'])));
    $useremail = $_POST['userEmail'];
    $userpassword = $_POST['userPassword'];
    $userrole = "user";
    $image = $_FILES['userImg'];

    //controling length
    if (strlen($username) < 3) {
        var_dump("invalid user Name");
        die;
    }
    if (strlen($userpassword) < 5) {
        var_dump("invalid password");
        die;
    }
    if (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
        var_dump("incorrect Email");
        die;
    }



    $connect = mysqli_connect("localhost", "root", "", "mydb");

    if (!$connect) {
        die("Connection failed!!!");
    }

    // Check if the email is already registered
    $check_sql = "SELECT * FROM users WHERE Email = ?";
    $check_stmt = mysqli_stmt_init($connect);

    if (mysqli_stmt_prepare($check_stmt, $check_sql)) {
        mysqli_stmt_bind_param($check_stmt, "s", $useremail);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_fetch_assoc($result)) {
            echo "This email is already registered";
            die;
        } else {

            $hashedPassword = password_hash($userpassword, PASSWORD_DEFAULT);

            // Insert new user
            $insert_sql = "INSERT INTO users (Name, Email, Password, role) VALUES (?, ?, ?, ?)";
            $insert_stmt = mysqli_stmt_init($connect);

            if (mysqli_stmt_prepare($insert_stmt, $insert_sql)) {
                mysqli_stmt_bind_param($insert_stmt, "ssss", $username, $useremail, $hashedPassword, $userrole);
                mysqli_stmt_execute($insert_stmt);
                $_SESSION['user_email'] = $useremail;
                //auto increment reson
                $_SESSION['user_id'] = mysqli_insert_id($connect);
                $_SESSION['user_role'] = $userrole;
                $_SESSION['user_name'] = $username;


                //uploading Image ----------------------------------------------->
                $imageName = $image['name'];
                $imageTmp  = $image['tmp_name'];
                $imageError = $image['error'];

                // Extract extension (e.g., .jpg, .png)
                $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);

                if ($imageError === 0) {
                    //cahnge all to username to lower and _
                    $cleanUsername = preg_replace('/[^a-z0-9]/', '_', strtolower($username));
                    $newImageName = $cleanUsername . '.' . $imageExt;
                    $destination = '../images/' . $newImageName;



                    if (!move_uploaded_file($imageTmp, $destination)) {
                        die("Image upload failed.");
                    }
                } else {
                    die("Please select a valid image file");
                }

                $_SESSION['imgname'] = $newImageName;

                header('Location: ../users/payments.php');
                exit();
            } else {
                echo "Failed to prepare registration query";
            }
        }
    }
}

?>




<!doctype html>
<html class="no-js" lang="en">

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
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>User Name</label>
                            <input type="text" required class="form-control" name="UserFullname" placeholder="User Name">
                        </div>
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" required class="form-control" name="userEmail" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" required class="form-control" name="userPassword" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label>Image (JPG)</label><br>
                            <input type="file" name="userImg" accept="image/*" required>
                        </div>
                        <button type="submit" name="sub" class="btn btn-primary btn-flat m-b-30 m-t-30">Register</button>
                        <div class="register-link m-t-15 text-center">
                            <p>Already have account ? <a href="page-login.php"> Sign in</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php include "../includes/footer_less.php"; ?>



</body>

</html>