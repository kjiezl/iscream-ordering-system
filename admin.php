<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

$query = mysqli_query($conn, "SELECT is_admin FROM user_info WHERE id = '$user_id'") or die('Query Failed');
$user = mysqli_fetch_assoc($query);

if (!$user || $user['is_admin'] != 1) {
    header('location:index.php');
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('location:login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <header>
        <h1>Admin Panel</h1>
        <div>
            <a href="admin.php?logout=true" onclick="return confirm('Are you sure you want to logout?');" class="btn">Logout</a>
        </div>
    </header>

    <section>
        <h2>Manage Products</h2>
        <a href="add_product.php" class="btn">Add Product</a>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $products_query = mysqli_query($conn, "SELECT * FROM `products`") or die('Query Failed');
                if (mysqli_num_rows($products_query) > 0) {
                    while ($product = mysqli_fetch_assoc($products_query)) {
                        echo "
                            <tr>
                                <td>{$product['id']}</td>
                                <td>{$product['name']}</td>
                                <td>₱{$product['price']}</td>
                                <td><img src='images/{$product['image']}' height='50'></td>
                                <td>
                                    <a href='edit_product.php?id={$product['id']}' class='btn'>Edit</a>
                                    <a href='delete_product.php?id={$product['id']}' class='btn' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No products available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Manage Orders</h2>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $orders_query = mysqli_query($conn, "SELECT * FROM `orders`") or die('Query Failed');
                if (mysqli_num_rows($orders_query) > 0) {
                    while ($order = mysqli_fetch_assoc($orders_query)) {
                        echo "
                            <tr>
                                <td>{$order['id']}</td>
                                <td>{$order['user_id']}</td>
                                <td>₱{$order['total_price']}</td>
                                <td>{$order['status']}</td>
                                <td>
                                    <form method='post' action='update_order_status.php'>
                                        <input type='hidden' name='order_id' value='{$order['id']}'>
                                        <select name='status'>
                                            <option value='Pending'" . ($order['status'] == 'Pending' ? ' selected' : '') . ">Pending</option>
                                            <option value='Shipped'" . ($order['status'] == 'Shipped' ? ' selected' : '') . ">Shipped</option>
                                            <option value='Delivered'" . ($order['status'] == 'Delivered' ? ' selected' : '') . ">Delivered</option>
                                        </select>
                                        <button type='submit' class='btn'>Update</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>