<?php
require_once 'config.php';

// Clear user session
unset($_SESSION['user_id']);
unset($_SESSION['username']);

// Redirect to homepage
redirect('index.php');
?>