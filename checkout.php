<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

$cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query Failed');
$grand_total = 0;

if (mysqli_num_rows($cart_query) > 0) {
    $insert_order = "INSERT INTO `orders` (user_id, total_price) VALUES ('$user_id', '$grand_total')";
    mysqli_query($conn, $insert_order) or die(mysqli_error($conn));

    $order_id = mysqli_insert_id($conn);

    while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
        $product_name = $fetch_cart['name'];
        $price = $fetch_cart['price'];
        $quantity = $fetch_cart['quantity'];
        $total_price = $price * $quantity;

        $insert_order_item = "INSERT INTO `order_items` (order_id, product_name, price, quantity, total_price) 
                              VALUES ('$order_id', '$product_name', '$price', '$quantity', '$total_price')";
        mysqli_query($conn, $insert_order_item) or die(mysqli_error($conn));

        $grand_total += $total_price;
    }

    mysqli_query($conn, "UPDATE `orders` SET total_price = '$grand_total' WHERE id = '$order_id'") or die(mysqli_error($conn));

    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die(mysqli_error($conn));

    echo "<script>alert('Order placed successfully!'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Your cart is empty!'); window.location.href='index.php';</script>";
}
?>