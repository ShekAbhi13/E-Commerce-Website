<?php

// Include the database configuration file
include 'config.php';

// Start the session to track user login state
session_start();

// Retrieve the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Redirect to login page if the user is not logged in
if (!isset($user_id)) {
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- Font Awesome CDN link for icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <!-- Include the header section -->
   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Your Orders</h3>
      <p> <a href="home.php">Home</a> / Orders </p>
   </div>

   <section class="placed-orders">
      <h1 class="title">Placed Orders</h1>

      <div class="box-container">

         <?php
         // Fetch orders from the database for the logged-in user
         $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('Query failed');

         // Check if there are any orders
         if (mysqli_num_rows($order_query) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
         ?>
               <div class="box">
                  <p> Placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                  <p> Name : <span><?php echo $fetch_orders['name']; ?></span> </p>
                  <p> Number : <span><?php echo $fetch_orders['number']; ?></span> </p>
                  <p> Email : <span><?php echo $fetch_orders['email']; ?></span> </p>
                  <p> Address : <span><?php echo $fetch_orders['address']; ?></span> </p>
                  <p> Payment method : <span><?php echo $fetch_orders['method']; ?></span> </p>
                  <p> Your orders : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
                  <p> Total price : <span>$<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
                  <p> Payment status : <span style="color:
                  <?php
                  // Display payment status color (red for pending, green for completed)
                  echo ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green';
                  ?>;">
                        <?php echo $fetch_orders['payment_status']; ?></span>
                  </p>
               </div>
         <?php
            }
         } else {
            // Show message if no orders are found
            echo '<p class="empty">No orders placed yet!</p>';
         }
         ?>
      </div>
   </section>

   <!-- Include the footer section -->
   <?php include 'footer.php'; ?>

   <!-- Custom JavaScript file link -->
   <script src="js/script.js"></script>

</body>

</html>