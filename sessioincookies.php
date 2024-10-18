<?php

session_start();

$_SESSION['user'] = $_POST['username'];
$_SESSION['pass'] = $_POST['password'];

if( !empty($_POST['check'])){
    setcookie('user',$_SESSION['user'], time() + 365*24*3600,null,null,false,true);
    setcookie('pass',$_SESSION['pass'], time() + 365*24*3600,null,null,false,true);
}

header('Location:choose.php');


?>