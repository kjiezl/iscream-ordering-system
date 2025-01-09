<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = $_POST['password'];

   $select = mysqli_query($conn, "SELECT * FROM `user_info` WHERE email = '$email'") or die(mysqli_error($conn));

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      if(password_verify($pass, $row['password'])){
         $_SESSION['user_id'] = $row['id'];
         header('location:index.php');
      } else {
         $message[] = 'Incorrect password!';
      }
   } else {
      $message[] = 'Incorrect email!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>iScream - Login</title>
   <link rel="stylesheet" href="css/login.css">
   <link rel="icon" href="images/logo.png" type="image/png">
   <script>
      window.onload = function() {
         var messages = document.querySelectorAll('.message');
         messages.forEach(function(msg) {
            setTimeout(function() {
               msg.remove();
            }, 3000);
         });
      };
   </script>
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $msg){
      echo '<div class="message show" onclick="this.remove();">'.$msg.'</div>';
   }
}
?>

<div class="form-container">
   <div class="logo-container">
      <img src="images/logo.png" alt="Logo" class="logo">
   </div>
   <form action="" method="post">
      <h3>Welcome back!</h3>
      <input type="email" name="email" required placeholder="Enter email" class="box">
      <input type="password" name="password" required placeholder="Enter password" class="box">
      <input type="submit" name="submit" class="btn" value="Login">
      <p>Don't have an account? <a href="register.php">Sign up</a></p>
   </form>
</div>

</body>
</html>