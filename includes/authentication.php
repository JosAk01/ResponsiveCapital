<?php
session_start();
if(!isset($_SESSION['user_id']) &&!isset($_SESSION['name'])){
	header("Location:login.php?error=The page you visted requires login");
exit();

}
?>