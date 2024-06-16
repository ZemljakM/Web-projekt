<?php
session_start();
session_destroy();

setcookie('user_logged_in', '', time() - (30 * 60), "/");
setcookie('user_id', '', time() - (30 * 60), "/");
setcookie('is_admin', '', time() - (30 * 60), "/");

header("Location: index.php");
exit();
?>