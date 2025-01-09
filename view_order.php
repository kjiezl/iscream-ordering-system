<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

$order_id = $_GET['id'];

$order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND id = '$order_id'") or die('Query Failed: No orders found.');
$order = mysqli_fetch_assoc($order_query);

$order_items_query = mysqli_query($conn, "SELECT * FROM `order_items` WHERE order_id = '$order_id'") or die('Query Failed: No order items found.');

if (!$order) {
    echo "<script>alert('Order not found!'); window.location.href='order_status.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="css/view_order.css">
</head>
<body>

    <div class="container">
        <h1>Order Details</h1>

        <div class="order-details">
            <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
            <p><strong>Total Price:</strong> $<?php echo $order['total_price']; ?></p>
            <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
        </div>

        <h2>Items in Order</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($order_items_query) > 0) {
                    while ($item = mysqli_fetch_assoc($order_items_query)) {
                        echo "
                        <tr>
                            <td>{$item['product_name']}</td>
                            <td>\${$item['price']}</td>
                            <td>{$item['quantity']}</td>
                            <td>\${$item['total_price']}</td>
                        </tr>";
                    }
                }
                ?>
            </tbody>
        </table>

        <a href="order_status.php" class="btn">Back to Orders</a>
    </div>

</body>
</html>