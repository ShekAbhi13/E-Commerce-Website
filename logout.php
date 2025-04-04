<?php

// Include the database configuration file
include 'config.php';

// Start the session to access session variables
session_start();

// Unset all session variables (removes stored user data)
session_unset();

// Destroy the current session (logs the user out completely)
session_destroy();

// Redirect the user to the login page after logging out
header('location:login.php');
