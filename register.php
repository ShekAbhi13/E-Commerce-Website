<?php

// Include the database configuration file
include 'config.php';

// Check if the registration form has been submitted
if (isset($_POST['submit'])) {

   // Retrieve user input from the form
   $name = $_POST['name'];
   $email = $_POST['email'];
   $pass = $_POST['password'];
   $cpass = $_POST['cpassword'];

   // Check if the user already exists in the database
   $query = "SELECT * FROM `users` WHERE email = '$email'";
   $select_users = mysqli_query($conn, $query) or die('Query failed');

   if (mysqli_num_rows($select_users) > 0) {
      // If the email is already registered, show an error message
      $message[] = 'User already exists!';
   } else {
      // Check if the password and confirm password match
      if ($pass != $cpass) {
         $message[] = 'Confirm password does not match!';
      } else {
         // Insert the new user into the database
         mysqli_query($conn, "INSERT INTO `users`(name, email, password) VALUES('$name', '$email', '$cpass')") or die('Query failed');

         // Show success message and redirect to login page
         header('location:login.php');
         $message[] = 'Registered successfully!';
         exit();
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
   <title>Register</title>

   <!-- Font Awesome for icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file -->
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php
   // Display messages if any exist
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
      }
   }
   ?>

   <!-- Registration Form Container -->
   <div class="form-container">
      <form action="" method="post">
         <h3>Register Now</h3>

         <input type="text" name="name" placeholder="Enter your name" required class="box">

         <input type="email" name="email" placeholder="Enter your email" required class="box">

         <input type="password" name="password" placeholder="Enter your password" required class="box">

         <input type="password" name="cpassword" placeholder="Confirm your password" required class="box">

         <input type="submit" name="submit" value="Register Now" class="btn">

         <p>Already have an account? <a href="login.php">Login Now</a></p>
      </form>
   </div>

</body>

</html>