<?php

// Include the configuration file to connect to the database
include 'config.php';

// Start the session to access user data
session_start();

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// If user is not logged in, redirect to the login page
if (!isset($user_id)) {
   header('location:login.php');
}

// Check if the "Order Now" button has been clicked
if (isset($_POST['order_btn'])) {

   // Retrieve form data
   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $address = $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $placed_on = date('d-M-Y'); // Store the current date

   $cart_total = 0; // Initialize total cart amount
   $cart_products = []; // Initialize an array to store cart products

   // Retrieve cart items for the logged-in user
   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if (mysqli_num_rows($cart_query) > 0) {
      while ($cart_item = mysqli_fetch_assoc($cart_query)) {
         $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   // Convert cart products array to a string
   $total_products = implode(', ', $cart_products);

   // Check if the same order already exists
   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   if ($cart_total == 0) {
      $message[] = 'Your cart is empty';
   } else {
      if (mysqli_num_rows($order_query) > 0) {
         $message[] = 'Order already placed!';
      } else {
         // Insert new order into the database
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
         $message[] = 'Order placed successfully!';

         // Clear the cart after placing the order
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
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
   <title>Checkout</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Checkout</h3>
      <p> <a href="home.php">Home</a> / Checkout </p>
   </div>

   <!-- Display Order Summary -->
   <section class="display-order">
      <?php
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if (mysqli_num_rows($select_cart) > 0) {
         while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
      ?>
            <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo 'Rs' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity']; ?>)</span> </p>
      <?php
         }
      } else {
         echo '<p class="empty">Your cart is empty</p>';
      }
      ?>
      <div class="grand-total"> Grand Total : <span>Rs <?php echo $grand_total; ?>/-</span> </div>
   </section>

   <!-- Checkout Form -->
   <section class="checkout">
      <form action="" method="post">
         <h3>Place Your Order</h3>
         <div class="flex">
            <div class="inputBox">
               <span>Your Name :</span>
               <input type="text" name="name" required placeholder="Enter your name">
            </div>
            <div class="inputBox">
               <span>Your Number :</span>
               <input type="text" name="number" required placeholder="Enter your number">
            </div>
            <div class="inputBox">
               <span>Your Email :</span>
               <input type="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="inputBox">
               <span>Payment Method :</span>
               <select name="method">
                  <option value="cash on delivery">Cash on Delivery</option>
                  <option value="credit card">Credit Card</option>
                  <option value="paypal">PayPal</option>
                  <option value="JuiceMCB">JuiceMCB</option>
               </select>
            </div>
            <div class="inputBox">
               <span>City :</span>
               <input type="text" name="city" required placeholder="e.g. Port Louis">
            </div>
            <div class="inputBox">
               <span>State :</span>
               <input type="text" name="state" placeholder="">
            </div>
            <div class="inputBox">
               <span>Country :</span>
               <input type="text" name="country" required placeholder="e.g. Mauritius">
            </div>
            <div class="inputBox">
               <span>Postal Code:</span>
               <input type="number" min="0" name="pin_code" required placeholder="e.g. 12345">
            </div>
            <div class="inputBox">
               <span>Street Address:</span>
               <input type="text" name="street" required placeholder="e.g. Street Name">
            </div>
         </div>
         <input type="submit" value="Order Now" class="btn" name="order_btn">
      </form>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Custom JS File Link -->
   <script src="js/script.js"></script>

</body>

</html>