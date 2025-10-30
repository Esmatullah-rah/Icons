<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start(); // Only start if not already started
}
session_unset();
session_destroy();

header('Location: index.php');
exit;
