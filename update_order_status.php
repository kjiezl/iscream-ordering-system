<?php
include 'config.php';

if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $query = "UPDATE `orders` SET status = '$status' WHERE id = '$order_id'";
    mysqli_query($conn, $query) or die('Query Failed');

    echo "<script>alert('Order status updated!'); window.location.href='admin.php';</script>";
}
?>