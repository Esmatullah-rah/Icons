<?php
$connect = mysqli_connect("localhost", "root", "", "mydb");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=payment_row_$id.csv");

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Card_id', 'Amount', 'Payment_count', 'Start_date', 'Exp_date', 'User_id']);

    $stmt = mysqli_prepare($connect, "SELECT ID, Name, Card_id, Amount, Payment_count, Start_date, Exp_date, User_id FROM payments WHERE ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();

} else {

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=all_payments_' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Card_id', 'Amount', 'Payment_count', 'Start_date', 'Exp_date', 'User_id']);

    $result = mysqli_query($connect, "SELECT ID, Name, Card_id, Amount, Payment_count, Start_date, Exp_date, User_id FROM payments");

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}
?>
