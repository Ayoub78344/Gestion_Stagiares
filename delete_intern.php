<?php
if(isset($_GET['id'])){
   
    include ("conection.php");
    $sql = "DELETE FROM intern WHERE Id_intern='{$_GET['id']}'";
    if(mysqli_query($conn,$sql)){
        echo "DELETED !";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    header('location:intern_list.php') ;
}
?>