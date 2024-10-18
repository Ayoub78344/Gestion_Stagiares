<?php
if(isset($_GET['id'])){
   
    include ("conection.php");
    $sql = "DELETE FROM internship WHERE Id_internship='{$_GET['id']}'";
    if(mysqli_query($conn,$sql)){
        echo "DELETED !";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    header('location:internship_list.php') ;
}
?>
