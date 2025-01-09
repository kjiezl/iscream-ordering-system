<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

$query = mysqli_query($conn, "SELECT is_admin FROM user_info WHERE id = '$user_id'") or die('Query Failed');
$user = mysqli_fetch_assoc($query);

if ($user['is_admin'] == 1) {
   header('location:admin.php');
   exit;
}

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
};

if(isset($_POST['add_to_cart'])){
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die(mysqli_error($conn));

   if(mysqli_num_rows($select_cart) > 0){
      $message[] = 'Product already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die(mysqli_error($conn));
      $message[] = 'Product added to cart!';
   }
};

if(isset($_POST['update_cart'])){
   $update_quantity = $_POST['cart_quantity'];
   $update_id = $_POST['cart_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die(mysqli_error($conn));
   $message[] = 'Cart quantity updated successfully!';
}

if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die(mysqli_error($conn));
   header('location:index.php');
}
  
if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die(mysqli_error($conn));
   header('location:index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>iScream Ice Cream Shop</title>
   <link rel="stylesheet" href="css/index.css">
   <link rel="icon" href="images/logo.png" type="image/png">
   </head>
<body>
   
<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>

<header>
   <div class="logo-container">
      <img src="images/logo.png" alt="iScream Shop Logo" class="logo">
      <h1 class="site-name">iScream Shop</h1>
   </div>
</header>

<div class="container">

   <div class="user-profile">
      <?php
         $select_user = mysqli_query($conn, "SELECT * FROM `user_info` WHERE id = '$user_id'") or die(mysqli_error($conn));
         if(mysqli_num_rows($select_user) > 0){
            $fetch_user = mysqli_fetch_assoc($select_user);
         };
      ?>
      <div class="profile-header">
         <p>Welcome, <span><?php echo $fetch_user['name']; ?></span>!</p>
         <p class="email-text">Email: <span><?php echo $fetch_user['email']; ?></span></p>
      </div>
      <div class="profile-actions">
         <a href="order_status.php" class="btn">Check Orders</a>
         <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Are you sure you want to logout?');" class="btn logout-btn">Logout</a>
      </div>
   </div>

   <div class="products">
      <h1 class="heading">Latest Flavors</h1>
      <div class="product-grid">
         <?php
            $select_product = mysqli_query($conn, "SELECT * FROM `products`") or die('Query Failed');
            if(mysqli_num_rows($select_product) > 0){
               while($fetch_product = mysqli_fetch_assoc($select_product)){
         ?>
         <form method="post" class="product-card" action="">
            <img src="images/<?php echo $fetch_product['image']; ?>" alt="">
            <div class="product-name"><?php echo $fetch_product['name']; ?></div>
            <div class="product-price">₱<?php echo $fetch_product['price']; ?></div>
            <input type="number" min="1" name="product_quantity" value="1" class="quantity-input">
            <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
            <input type="submit" value="Add to Cart" name="add_to_cart" class="add-to-cart-btn">
         </form>
         <?php
            };
         };
         ?>
      </div>
   </div>

   <div class="shopping-cart">
      <h1 class="heading">Your Shopping Cart</h1>
      <table>
         <thead>
            <tr>
               <th>Image</th>
               <th>Name</th>
               <th>Price</th>
               <th>Quantity</th>
               <th>Total Price</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query Failed');
               $grand_total = 0;
               if(mysqli_num_rows($cart_query) > 0){
                  while($fetch_cart = mysqli_fetch_assoc($cart_query)){
            ?>
            <tr>
               <td><img src="images/<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
               <td><?php echo $fetch_cart['name']; ?></td>
               <td>₱<?php echo $fetch_cart['price']; ?></td>
               <td>
                  <form action="" method="post">
                     <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                     <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" class="quantity-input">
                     <input type="submit" name="update_cart" value="Update" class="btn update-btn">
                  </form>
               </td>
               <td>₱<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></td>
               <td><a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="btn remove-btn" onclick="return confirm('Remove item from cart?');">Remove</a></td>
            </tr>
            <?php
            $grand_total += $sub_total;
               }
            }else{
               echo '<tr><td colspan="6">No items in cart.</td></tr>';
            }
            ?>
            <tr class="total-row">
               <td colspan="4">Total Price:</td>
               <td>₱<?php echo $grand_total; ?></td>
               <td><a href="index.php?delete_all" class="btn delete-all-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('Delete all from cart?');">Delete All</a></td>
            </tr>
         </tbody>
      </table>
      <div class="cart-btn">  
         <a href="checkout.php" class="btn checkout-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">Proceed to Checkout</a>
      </div>
   </div>

</div>

</body>
</html>