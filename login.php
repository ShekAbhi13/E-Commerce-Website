<?php

// Include the database configuration file
include 'config.php';

// Start a new session
session_start();

// Check if the login form has been submitted
if (isset($_POST['submit'])) {

   // Retrieve email and password from the form
   $email = $_POST['email'];
   $pass = $_POST['password'];

   // Query the database to check if the user exists
   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('Query failed');

   // Check if any matching user was found
   if (mysqli_num_rows($select_users) > 0) {

      // Fetch user data
      $row = mysqli_fetch_assoc($select_users);

      if ($row) {
         // Store user details in session variables
         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];
         $_SESSION['user_id'] = $row['id'];

         // Redirect to the home page after successful login
         header('location:home.php');
      }
   } else {
      // Store an error message if login fails
      $message[] = 'Incorrect email or password!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- Font Awesome for icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file -->
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php
   // Display error messages if any exist
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

   <!-- Login Form Container -->
   <div class="form-container">
      <form action="" method="post">
         <h3>Login Now</h3>


         <input type="email" name="email" placeholder="Enter your email" required class="box">


         <input type="password" name="password" placeholder="Enter your password" required class="box">


         <input type="submit" name="submit" value="Login Now" class="btn">


         <p>Don't have an account? <a href="register.php">Register Now</a></p>
      </form>
   </div>

</body>

</html>