<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'images/' . $image;

    $query = "INSERT INTO `products` (name, price, image) VALUES ('$name', '$price', '$image')";
    mysqli_query($conn, $query) or die('Query Failed');

    move_uploaded_file($image_tmp_name, $image_folder);

    echo "<script>alert('Product added successfully!'); window.location.href='admin.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="css/add_product.css">
</head>
<body>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Add New Product</h3>
            <input type="text" name="name" required placeholder="Enter product name">
            <input type="number" name="price" required placeholder="Enter product price">
            <input type="file" name="image" required>
            <input type="submit" name="submit" value="Add Product">
            <a href="admin.php"><button type="button" class="cancel-btn">Cancel</button></a>
        </form>
    </div>
</body>
</html>