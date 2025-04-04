<?php
// Check if there are any messages set and display them
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

<header class="header">

   <!-- First header section: Social media links and login/register buttons -->
   <div class="header-1">
      <div class="flex">

         <!-- Social media links -->
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>

         <!-- Login and Register links -->
         <p>
            <a href="login.php" class="login-btn">Login</a>
            <a href="register.php" class="register-btn">Register</a>
         </p>
      </div>
   </div>

   <!-- Second header section: Logo, Navigation bar, Icons -->
   <div class="header-2">
      <div class="flex">

         <a href="home.php" class="logo">Book<span class="logo-part">Nest</span></a>

         <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="shop.php">Shop</a>
            <a href="contact.php">Contact</a>
            <a href="orders.php">Orders</a>
         </nav>

         <!-- Header icons: Menu, Search, User, and Cart -->
         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div> <!-- Mobile menu button -->
            <a href="search_page.php" class="fas fa-search"></a> <!-- Search icon -->
            <div id="user-btn" class="fas fa-user"></div> <!-- User profile button -->

            <?php
            // Query to count the number of items in the cart for the logged-in user
            $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            $cart_rows_number = mysqli_num_rows($select_cart_number);
            ?>

            <!-- Cart icon with item count -->
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>

         <!-- User box: Displays logged-in user's name and email -->
         <div class="user-box">
            <p>Username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>Email : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="logout.php" class="delete-btn">logout</a> <!-- Logout button -->
         </div>
      </div>
   </div>

</header>