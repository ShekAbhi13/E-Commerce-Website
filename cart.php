<?php

// Include the database configuration file
include 'config.php';

// Start the session
session_start();

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Redirect to login page if user is not logged in
if (!isset($user_id)) {
   header('location:login.php');
}

// Update cart quantity if the update button is clicked
if (isset($_POST['update_cart'])) {
   $cart_id = $_POST['cart_id']; // Retrieve cart item ID
   $cart_quantity = $_POST['cart_quantity']; // Retrieve new quantity
   // Update the cart quantity in the database
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
   $message[] = 'cart quantity updated!';
}

// Delete a specific item from the cart
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete']; // Retrieve item ID to delete
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php'); // Refresh the cart page
}

// Delete all items from the cart for the current user
if (isset($_GET['delete_all'])) {
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php'); // Refresh the cart page
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>shopping cart</h3>
      <p> <a href="home.php">home</a> / cart </p>
   </div>

   <section class="shopping-cart">

      <h1 class="title">products added</h1>

      <div class="box-container">
         <?php
         $grand_total = 0;
         // Retrieve cart items for the logged-in user
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
         ?>
               <div class="box">
                  <!-- Delete single cart item -->
                  <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Delete this from cart?');"></a>
                  <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_cart['name']; ?></div>
                  <div class="price">Rs <?php echo $fetch_cart['price']; ?>/-</div>
                  <form action="" method="post">
                     <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                     <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                     <input type="submit" name="update_cart" value="update" class="option-btn">
                  </form>
                  <!-- Calculate subtotal for each item -->
                  <div class="sub-total"> sub total : <span>Rs <?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>/-</span> </div>
               </div>
         <?php
               $grand_total += $sub_total; // Calculate grand total
            }
         } else {
            echo '<p class="empty">your cart is empty</p>';
         }
         ?>
      </div>

      <div style="margin-top: 2rem; text-align:center;">
         <!-- Delete all cart items -->
         <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('delete all from cart?');">delete all</a>
      </div>

      <div class="cart-total">
         <p>grand total : <span>Rs <?php echo $grand_total; ?>/-</span></p>
         <div class="flex">
            <a href="shop.php" class="option-btn">continue shopping</a>
            <a href="checkout.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">proceed to checkout</a>
         </div>
      </div>

   </section>

   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>