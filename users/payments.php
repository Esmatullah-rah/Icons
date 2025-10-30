<?php
session_start();
date_default_timezone_set('Asia/Kabul');

$user_id = $_SESSION['user_id'] ?? null;

if (!isset($user_id)) {
    header("Location: ../admin/index.php");
    exit();
}

$connect = mysqli_connect("localhost", "root", "", "mydb");

if (isset($_POST['payBtn'])) {

    if (
        empty($_POST['payname']) || empty($_POST['paynumber']) ||
        strlen($_POST['paynumber']) < 5 || !is_numeric(($_POST['paynumber']))
    ) {
        die('SOMETHIN IS WRONG!!!');
    } else if (isset($_POST['payBtn']) && isset($_POST['payname'], $_POST['paynumber']) && $user_id) {

        $payFullName = $_POST['payname'];
        $payCardId = $_POST['paynumber'];
        $pay_amount = 10;
        $current_time = date('Y-m-d H:i:s');
        $exp_time = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($current_time)));



        // Check existing payment
        $check = mysqli_prepare($connect, "SELECT ID, Exp_date, Payment_count FROM payments WHERE User_id = ?");
        mysqli_stmt_bind_param($check, "i", $user_id);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);
        $payment = mysqli_fetch_assoc($result);

        if (!$payment) {
            $payment_count = 1;

            $insert = mysqli_prepare(
                $connect,
                "INSERT INTO payments (Name, Card_id, Amount, Payment_count, Start_date, Exp_date, User_id) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($insert, "siiissi", $payFullName, $payCardId, $pay_amount, $payment_count, $current_time, $exp_time, $user_id);
            mysqli_stmt_execute($insert);

            header("Location: ../users/user_index.php");
            exit();
        } else {

            if (strtotime($payment['Exp_date']) < time()) {

                $new_count = $payment['Payment_count'] + 1;

                // Expired
                $update = mysqli_prepare(
                    $connect,
                    "UPDATE payments SET Name = ?, Card_id = ?,  Start_date = ?, Exp_date = ?,  Amount = ?, Payment_count = ? WHERE User_id = ?"
                );
                mysqli_stmt_bind_param($update, "sissiii", $payFullName, $payCardId, $current_time, $exp_time, $pay_amount, $new_count,  $user_id);
                mysqli_stmt_execute($update);

                header("Location: ../users/user_index.php");
                exit();
            } else {
                header("Location: ../users/user_index.php");
                exit();
            }
        }
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

<body>


    <div class="col-lg-12 d-flex justify-content-center">
        <div class="card col-md-6 mt-5">
            <div class="card-header">
                <strong class="card-title">Credit Card ( <u>ONE DAY</u> )</strong>
            </div>
            <div class="card-body">
                <!-- Credit Card -->
                <div id="pay-invoice">
                    <div class="card-body">
                        <div class="card-title">
                            <h3 class="text-center">Pay Invoice</h3>
                        </div>
                        <hr>
                        <form method="post">
                            <div class="form-group text-center">
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="text-muted fa fa-cc-visa fa-2x"></i></li>
                                    <li class="list-inline-item"><i class="fa fa-cc-mastercard fa-2x"></i></li>
                                    <li class="list-inline-item"><i class="fa fa-cc-amex fa-2x"></i></li>
                                    <li class="list-inline-item"><i class="fa fa-cc-discover fa-2x"></i></li>
                                </ul>
                            </div>
                            <div class="form-group has-success">
                                <label for="cc-name" class="control-label mb-1">Name on card</label>
                                <input id="cc-name" name="payname" placeholder="Full Name" type="text" class="form-control cc-name valid">
                                <span class="help-block field-validation-valid" data-valmsg-for="cc-name" data-valmsg-replace="true"></span>
                            </div>
                            <div class="form-group">
                                <label for="cc-number" class="control-label mb-1">Card number</label>
                                <input id="cc-number" name="paynumber" type="tel" class="form-control cc-number identified visa" placeholder="0093566865">
                                <span class="help-block" data-valmsg-for="cc-number" data-valmsg-replace="true"></span>
                            </div>
                            <div class=" text-center " href="user_index.php">
                                <button id="payment-button" type="submit" name="payBtn" class="btn btn-lg btn-info btn-block">
                                    <i class="fa fa-lock fa-lg"></i>&nbsp;
                                    <span id="payment-button-amount">Pay $10.00</span>
                                    <span id="payment-button-sending" style="display:none;">Sending…</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class=" ">

            </div>

        </div> <!-- .card -->

    </div>





</body>

</html>