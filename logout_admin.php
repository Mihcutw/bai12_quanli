<?php
session_start();
unset($_SESSION['admin_id']);
header('Location: login_admin.php');
exit;
?>