<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

$order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('Query Failed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <link rel="stylesheet" href="css/order_status.css">
    <link rel="icon" href="images/logo.png" type="image/png">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="images/logo.png" alt="iScream Shop Logo" class="logo">
            <h1 class="site-name">iScream Shop</h1>
        </div>
    </header>
    <div class="container">
        <h1>Your Order Status</h1>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($order_query) > 0) {
                    while ($order = mysqli_fetch_assoc($order_query)) {
                        echo "
                        <tr>
                            <td>{$order['id']}</td>
                            <td>₱{$order['total_price']}</td>
                            <td>{$order['status']}</td>
                            <td><a href='view_order.php?id={$order['id']}' class='btn'>View Details</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='no-orders'>You have no orders yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="index.php" class="back-btn">Back to Home</a>
    </div>

</body>
</html>