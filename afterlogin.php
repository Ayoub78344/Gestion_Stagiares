<?php 
session_start();
require_once('conection.php');

if (empty($_SESSION['user'])) {
    echo 'Session user is empty. Please log in.';
} else {
    
    echo 'Hello, Dear '.$_SESSION['user'];
   
}
?>
