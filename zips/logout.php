
<?php
/**
 * ZIPS Chau E-Library - Logout
 */
session_start();

// Clear session
$_SESSION = [];
session_destroy();

// Redirect to auth page
header('Location: auth.php');
exit;
