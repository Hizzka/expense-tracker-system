<?php
require_once 'config.php';

// Destroy session and logout
session_destroy();
header('Location: login.php');
exit();
?>
