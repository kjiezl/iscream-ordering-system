<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

$cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
$grand_total = 0;

if (mysqli_num_rows($cart_query) > 0) {
    while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
        $grand_total += $fetch_cart['price'] * $fetch_cart['quantity'];
    }

    $cart_id = $user_id;

    $insert_order = "INSERT INTO `orders` (user_id, cart_id, total_price) VALUES ('$user_id', '$cart_id', '$grand_total')";
    mysqli_query($conn, $insert_order) or die(mysqli_error($conn));

    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die(mysqli_error($conn));

    echo "<script>alert('Order placed successfully!'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Your cart is empty!'); window.location.href='index.php';</script>";
}
?>
