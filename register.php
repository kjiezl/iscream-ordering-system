<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = $_POST['password'];
   $cpass = $_POST['cpassword'];

   if($pass != $cpass){
      $message[] = 'Passwords do not match!';
   } else {
      $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);  

      $select = mysqli_query($conn, "SELECT * FROM `user_info` WHERE email = '$email'") or die(mysqli_error($conn));

      if(mysqli_num_rows($select) > 0){
         $message[] = 'User already exists!';
      } else {
         mysqli_query($conn, "INSERT INTO `user_info`(name, email, password) VALUES('$name', '$email', '$hashed_pass')") or die(mysqli_error($conn));
         $message[] = 'Signed up successfully!';
         header('location:login.php');
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>iScream - Sign Up</title>
   <link rel="stylesheet" href="css/register.css">
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
      <h3>Create an Account</h3>
      <input type="text" name="name" required placeholder="Enter username" class="box">
      <input type="email" name="email" required placeholder="Enter email" class="box">
      <input type="password" name="password" required placeholder="Enter password" class="box">
      <input type="password" name="cpassword" required placeholder="Confirm password" class="box">
      <input type="submit" name="submit" class="btn" value="Sign Up">
      <p>Already have an account? <a href="login.php">Login</a></p>
   </form>

</div>

</body>
</html>