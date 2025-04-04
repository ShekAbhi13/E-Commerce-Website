<?php

// Include the configuration file to establish a database connection
include 'config.php';

// Start the session to track user login status
session_start();

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// If the user is not logged in, redirect them to the login page
if (!isset($user_id)) {
   header('location:login.php');
}

// Define the target directory for uploaded scripts
$targetDir = "uploaded_scripts/";

// Check if the form has been submitted
if (isset($_POST['send'])) {

   // <--- File Upload Handling --->
   if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
      $filename = basename($_FILES["file"]["name"]);
      $targetPath = $targetDir . $filename;

      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
         // Insert file details into the database
         $sql = "INSERT INTO files (filename, filepath, user_id) VALUES ('$filename', '$targetPath', '$user_id')";
         $result = mysqli_query($conn, $sql);

         if ($result) {
            $message[] = 'File uploaded successfully';
         } else {
            $message[] = 'Error storing file information in database';
         }
      } else {
         $message[] = 'Error moving uploaded file';
      }
   } else {
      $message[] = 'File upload failed!';
   }

   // Retrieve form inputs
   $name = $_POST['name'];
   $email = $_POST['email'];
   $number = $_POST['number'];
   $msg = $_POST['message'];

   // Check if the same message has already been sent
   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if (mysqli_num_rows($select_message) > 0) {
      $message[] = 'Message already sent!';
   } else {
      // Insert the message into the database
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'Message sent successfully!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <!-- Include the header -->
   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Contact Us</h3>
      <p> <a href="home.php">Home</a> / Contact </p>
   </div>

   <section class="contact">
      <!-- Contact Form -->
      <form action="" method="post" enctype="multipart/form-data">
         <h3>Say something or send us your script for review!</h3>
         <input type="text" name="name" required placeholder="Enter your name" class="box">
         <input type="email" name="email" required placeholder="Enter your email" class="box">
         <input type="number" name="number" required placeholder="Enter your number" class="box">
         <textarea name="message" class="box" placeholder="Enter your message" cols="30" rows="10"></textarea>
         <input type="file" name="file" class="box" accept=".pdf,.doc,.docx,.txt">
         <input type="submit" value="Send Message" name="send" class="btn">
      </form>
   </section>

   <!-- Include the footer -->
   <?php include 'footer.php'; ?>

   <!-- Custom JavaScript file link -->
   <script src="js/script.js"></script>

</body>

</html>