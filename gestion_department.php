<?php
include('conection.php');

if (isset($_POST['Submit'])) {
    $Name_depart = mysqli_real_escape_string($conn, $_POST['Name_depart']);
    $Id_admin = $_GET['id']; 

    if (isset($Name_depart) && isset($Id_admin)) {
        $sql = "INSERT INTO department (Id_admin, Name_depart) VALUES ('$Id_admin', '$Name_depart')";
        if (mysqli_query($conn, $sql)) {
            header('Location: department_list.php');
            exit(); 
        } else {
            die("There was an error inserting the data.");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Add a New Department</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="gestion_departement.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

</head>
<body>
<header>
    <div class="logo">
        <a href="#">
            <img src="imgs/output9.png" alt="Logo">
        </a>
    </div>

    <div class="parameters">
        <a href="department_list.php"><i class="fas fa-th-list"></i></a>
        <a href="intern_list.php"><i class="fas fa-users"></i></a>
        <a href="internship_list.php"><i class="fas fa-building"></i></a>
        <a href="results_list.php"><i class="fas fa-chart-pie"></i></a>
        <a href="skills.php"><i class="fas fa-award"></i></a>
        <?php if (isset($_SESSION['username'])): ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
        <?php else: ?>
            <a href="login.php"><i class="fas fa-sign-in-alt"></i></a>
        <?php endif; ?>
    </div>
</header>
<div class="container4">
    <h1 style="text-align: center;">Add a New Department</h1>
    <form method="POST" style="text-align: center;">
        <div class="form-group" style="display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-building" style="margin-right: 5px; color: black;"></i>
            <label for="Name_depart" style="margin: 0; color: black;">Department Name:</label>
        </div>
        <input type="text" id="Name_depart" name="Name_depart" placeholder="Enter the Department" autocomplete="off" style="margin-top: 10px;">
        <button type="submit" name="Submit" style="margin-top: 10px;">Submit</button>
    </form>
</div>



</body>
</html>