<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

$order_id = $_GET['id'];

$order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND id = '$order_id'") or die('Query Failed');
$order = mysqli_fetch_assoc($order_query);

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
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Main Container */
        .container {
            width: 80%;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        /* Order Details Section */
        .order-details {
            margin-bottom: 20px;
        }

        .order-details p {
            font-size: 1.2rem;
            color: #555;
            margin: 5px 0;
        }

        /* Items Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: #fff;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Action Button Styling */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #218838;
        }
    </style>
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
                </tr>
            </thead>
            <tbody>
                <?php
                $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' AND order_id = '{$order['id']}'") or die('Query Failed');
                while ($item = mysqli_fetch_assoc($cart_query)) {
                    echo "
                    <tr>
                        <td>{$item['name']}</td>
                        <td>\${$item['price']}</td>
                        <td>{$item['quantity']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="order_status.php" class="btn">Back to Orders</a>
    </div>

</body>
</html>