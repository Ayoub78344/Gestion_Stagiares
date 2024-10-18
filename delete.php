<?php
if(isset($_GET['id'])){
   
    include ("conection.php");
    $sql = "DELETE FROM department WHERE Id_depart='{$_GET['id']}'";
    if(mysqli_query($conn,$sql)){
        echo "DELETED !";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    header('location:department_list.php') ;
}
?>
