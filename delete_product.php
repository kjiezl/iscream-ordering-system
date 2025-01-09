<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$id'") or die('Query Failed');
    echo "<script>alert('Product deleted successfully!'); window.location.href='admin.php';</script>";
}
?>