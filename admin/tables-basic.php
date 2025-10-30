<?php
session_start();

$connect = mysqli_connect("localhost", "root", "", "mydb");
$result2 = mysqli_query($connect, "SELECT * FROM payments");

$counter = 0;

if (isset($_GET['delete_id'])) {
    $DeleteID = $_GET['delete_id'];
    mysqli_query($connect, "DELETE FROM users WHERE ID = $DeleteID");
}

// user table controler buttons --------------------->
$limit = 5;

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

$offset = ($page - 1) * $limit;

$total_result = mysqli_query($connect, "SELECT COUNT(*) AS total FROM users");
$total_row = mysqli_fetch_assoc($total_result);
$total_users = $total_row['total'];

//a simple division
$total_pages = ceil($total_users / $limit);

$result = mysqli_query($connect, "SELECT * FROM users ORDER BY id DESC LIMIT $limit OFFSET $offset");


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
                            <li><a href="#">Table</a></li>
                            <li class="active">Basic table</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>



        <div class="content mt-3 text-center ">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Users Table</strong>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Databasse ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Operations</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?= ++$counter ?></td>
                                                <td><?= $row['ID'] ?></td>
                                                <td><?= htmlspecialchars($row['Name']) ?></td>
                                                <td><?= htmlspecialchars($row['Email']) ?></td>
                                                <td><?= $row['role'] ?></td>
                                                <td>
                                                    <a class=" btn btn-info" href="edit.php?edit_id=<?= $row['ID'] ?>">Edit</a>
                                                    <a class=" btn btn-danger" href="?delete_id=<?= $row['ID'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>

                                <br>

                                <!-- controlers buttons -->
                                <div align="right">
                                    <?php if ($page > 1): ?>
                                        <a class="btn btn-secondary " href="?page=<?= $page - 1 ?>">Previous</a>
                                    <?php endif; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <a class="btn btn-secondary" href="?page=<?= $page + 1 ?>">Next</a>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>


                <h2 class="text-center mt-5">Paid People</h2>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Payments Table</strong>
                            </div>
                            <div class="card-body text-center">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Card Name</th>
                                            <th scope="col">Card id</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Payment_count</th>
                                            <th scope="col">Start_date</th>
                                            <th scope="col">Exp_date</th>
                                            <th scope="col">User_id</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($rov = mysqli_fetch_assoc($result2)): ?>
                                            <tr>
                                                <td><?= $rov['ID'] ?></td>
                                                <td><?= htmlspecialchars($rov['Name']) ?></td>
                                                <td><?= $rov['Card_id'] ?></td>
                                                <td><?= $rov['Amount'] ?></td>
                                                <td><?= $rov['Payment_count'] ?></td>
                                                <td><?= $rov['Start_date'] ?></td>
                                                <td><?= $rov['Exp_date'] ?></td>
                                                <td><?= $rov['User_id'] ?></td>

                                                <td>
                                                    <a class="btn btn-success" href="export_payment.php?id=<?= $rov['ID'] ?>">Get CSV</a>
                                                </td>

                                            </tr>

                                        <?php endwhile; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                <a href="export_payment.php" class="btn btn-success mt-2">Download All Payments as CSV</a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- .animated -->
        </div><!-- .content -->


    </div><!-- /#right-panel -->

    <!-- Right Panel -->


    <?php include "../includes/footer_less.php"; ?>



</body>

</html>