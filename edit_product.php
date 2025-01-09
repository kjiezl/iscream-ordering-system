<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$id'") or die('Query Failed');
    $product = mysqli_fetch_assoc($query);
}

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'images/' . $image;

    if (!empty($image)) {
        move_uploaded_file($image_tmp_name, $image_folder);
        $query = "UPDATE `products` SET name = '$name', price = '$price', image = '$image' WHERE id = '$id'";
    } else {
        $query = "UPDATE `products` SET name = '$name', price = '$price' WHERE id = '$id'";
    }

    mysqli_query($conn, $query) or die('Query Failed');
    echo "<script>alert('Product updated successfully!'); window.location.href='admin.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/edit_product.css">
</head>
<body>

<form action="" method="post" enctype="multipart/form-data">
    <h3>Edit Product</h3>
    <input type="text" name="name" value="<?php echo $product['name']; ?>" required placeholder="Enter product name">
    <input type="number" name="price" value="<?php echo $product['price']; ?>" required placeholder="Enter product price">
    <input type="file" name="image">
    <input type="submit" name="update" value="Update Product">
    <a href="admin.php" class="cancel-btn">Cancel</a>
</form>

</body>
</html>