<?php
if(isset($_GET['id'])){
   
    include ("conection.php");
    $sql = "DELETE FROM results WHERE Id_Result='{$_GET['id']}'";
    if(mysqli_query($conn,$sql)){
        echo "DELETED !";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    header('location:results_list.php') ;
}
?>
