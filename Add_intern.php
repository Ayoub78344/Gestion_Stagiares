<?php
include('conection.php');
$result = NULL;
if(isset($_POST['Submit'])){
    $first_name = mysqli_real_escape_string($conn,$_POST['First_name_intern']);
    $last_name = mysqli_real_escape_string($conn,$_POST['Last_name_intern']);
    $birthday = mysqli_real_escape_string($conn,$_POST['Birtday_intern']);
    $niveau = $_POST['Niveau'] ?? '';  
    $sql="INSERT INTO intern (Id_admin, First_name_intern, Last_name_intern, Birthday_intern,Niveau) VALUES ('{$_GET['id']}', '$first_name', '$last_name', '$birthday','$niveau ')";
    if(mysqli_query($conn,$sql)){
        echo "Data Inserted Successfully";
    } else {
        die("There is an error");
    }
    header('location:intern_list.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Add Intern</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="Add_intern.css">
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

<div class="container" style="text-align: center;">
    <h1 style="color: black;">Add a New Intern</h1>
    <form method="POST">
        <div class="form-group" style="display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-user" style="margin-right: 5px; color: black;"></i>
            <label for="first_name" style="margin: 0; color: black;">First Name:</label>
        </div>
        <input type="text" id="first_name" name="First_name_intern" placeholder="Enter the First name of the intern..." autocomplete="off" style="margin: 10px auto; display: block;">

        <div class="form-group" style="display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-user" style="margin-right: 5px; color: black;"></i>
            <label for="last_name" style="margin: 0; color: black;">Last Name:</label>
        </div>
        <input type="text" id="last_name" name="Last_name_intern" placeholder="Enter the Last name of the intern..." autocomplete="off" style="margin: 10px auto; display: block;">

        <div class="form-group" style="display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-calendar" style="margin-right: 5px; color: black;"></i>
            <label for="birthday" style="margin: 0; color: black;">Birthday:</label>
        </div>
        <input type="date" id="birthday" name="Birthday_intern" autocomplete="off" style="margin: 10px auto; display: block;">

        <div class="form-group" style="display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-graduation-cap" style="margin-right: 5px; color: black;"></i>
            <label for="Niveau" style="margin: 0; color: black;">Niveau:</label>
        </div>
        <input type="text" name="Niveau" placeholder="Niveau..." style="margin: 10px auto; display: block;">
        
        <button type="submit" name="Submit" style="margin-top: 10px;">Submit</button>
    </form>
</div>

</body>
</html>
