<?php
include ('conection.php');
session_start();
if( isset($_SESSION['user']) && isset($_SESSION['pass']) ){
    if( empty($_SESSION['user']) || empty($_SESSION['pass']) ){
        header('Location: login.php?error=username and password invalid');
    }else{
        $sql = "SELECT * FROM administration WHERE User_Name_adminn  = '{$_SESSION['user']}' ";
        $result = mysqli_query($conn, $sql);

        $row = mysqli_fetch_array($result);

        if( $row['Password_adminn'] === $_SESSION['pass'] ){

            $query ="SELECT * FROM department WHERE Id_admin = '{$row['Id_adminn']}'";
            $queryResult = mysqli_query($conn, $query);

        }else{
            header('Location: login.php?error=password invalid');
        }
    }
}else{
    header('Location: login.php?error=username and password invalid'); 
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Department_list</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="departement_list.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    
    <script type='text/javascript'>
        function chkalert(id) {
            var confirmDelete = confirm("Are you sure you want to remove this department?");
            if (confirmDelete) {
                window.location.href = "delete.php?id=" + id;
            } else {
                alert("Delete operation canceled.");
            }
        }
    </script>

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

<div class="container">
    <h2><?php if(isset($_SESSION['user'])) echo 'Hello DEAR '.$_SESSION['user'] ?></h2>
    <h1>Here's the list of the departments:</h1>

    <a href="gestion_department.php?id=<?php echo $row['Id_adminn'] ?>" class="btn-primary">Add</a>

    <table class="table table-hover text-center">
        <thead class="table-dark">
            <tr>
                <th>Departments name :</th>
                <th>Edit :</th>
                <th>Delete :</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($queryResult): ?>
                <?php foreach($queryResult as $line): ?>
                <tr>
                    <td><?php echo htmlspecialchars($line['Name_depart']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $line['Id_depart']?>"><i class="fas fa-edit edit-icon"></i></a>
                    </td>
                    <td>
                        <a href="javascript:void(0);" onclick="chkalert(<?php echo $line['Id_depart']; ?>)"><i class="fas fa-trash-alt delete-icon"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No departments found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
