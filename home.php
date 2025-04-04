<?php

// Include the database configuration file
include 'config.php';

// Start a new session or resume the existing session
session_start();

// Retrieve the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Redirect to login page if user is not logged in
if (!isset($user_id)) {
   header('location:login.php');
}

// Check if the "Add to Cart" button has been clicked
if (isset($_POST['add_to_cart'])) {

   // Retrieve product details from the form
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Check if the product is already in the cart for this user
   $query = "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'";
   $check_cart_numbers = mysqli_query($conn, $query) or die('Query failed');

   if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'Already added to cart!';
   } else {
      // Insert the selected product into the cart table
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) 
                           VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')")
         or die('Query failed');
      $message[] = 'Product added to cart!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>
   <!-- Font Awesome for icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <!-- Custom CSS file -->
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <!-- Include the header section -->
   <?php include 'header.php'; ?>

   <!-- Home Section -->
   <section class="home">
      <div class="content">
         <h3>Readers are Leaders.</h3>
         <p>Welcome to our bookstore. Discover a world of knowledge and imagination.</p>
         <a href="about.php" class="white-btn">Discover More</a>
      </div>
   </section>

   <!-- Products Section -->
   <section class="products">
      <h1 class="title">Latest Products</h1>
      <div class="box-container">

         <?php
         // Fetch the latest 6 products from the database
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('Query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <form action="" method="post" class="box">
                  <!-- Display product details -->
                  <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="Product Image">
                  <div class="name"> <?php echo $fetch_products['name']; ?> </div>
                  <div class="price">Rs <?php echo $fetch_products['price']; ?>/-</div>
                  <input type="number" min="1" name="product_quantity" value="1" class="qty">
                  <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                  <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                  <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
         ?>
      </div>


      <div class="load-more" style="margin-top: 2rem; text-align:center">
         <a href="shop.php" class="option-btn">Load More</a>
      </div>
   </section>

   <!-- About Section -->
   <section class="about">
      <div class="flex">
         <div class="image">
            <img src="images/about-img.png" alt="About Us">
         </div>
         <div class="content">
            <h3>About Us</h3>
            <p>Learn more about our bookstore and what we have to offer.</p>
            <a href="about.php" class="btn">Read More</a>
         </div>
      </div>
   </section>

   <!-- Contact Section -->
   <section class="home-contact">
      <div class="content">
         <h3>Have any questions?</h3>
         <p>Contact us for inquiries, book recommendations, or any assistance.</p>
         <a href="contact.php" class="white-btn">Contact Us</a>
      </div>
   </section>

   <!-- Include the footer section -->
   <?php include 'footer.php'; ?>

   <script src="js/script.js"></script>
</body>

</html>