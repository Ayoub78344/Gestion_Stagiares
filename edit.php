<?php
include('conection.php');

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current department name based on the provided ID
    $sql = "SELECT Name_depart FROM department WHERE Id_depart='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $current_name = $row['Name_depart'];
    } else {
        $current_name = ''; // Default if no department is found
    }

    // Handle form submission for updating department name
    if (isset($_POST['Edit'])) {
        $name = $_POST['new_name'];

        if (!empty($name)) {
            $sql = "UPDATE department SET Name_depart = '$name' WHERE Id_depart='$id'";
            $res = mysqli_query($conn, $sql);
            if ($res) {
                header('Location: department_list.php');
                exit(); // Ensure no further code is executed after the redirect
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        } else {
            echo "Please enter a valid department name.";
        }
    }
} else {
    echo "No department ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Edit Department</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="edit.css">
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
    <h1 style="text-align: center; color: black;">Edit the Name of the Department</h1>
    <form method="POST" style="text-align: center;">
        <div class="form-group" style="display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-building" style="margin-right: 5px; color: black;"></i>
            <label for="new_name" style="margin: 0; color: black;">Department Name:</label>
        </div>
        <input type="text" id="new_name" name="new_name" placeholder="Change Department Name" value="<?php echo htmlspecialchars($current_name); ?>" style="margin-top: 10px; display: block; margin-left: auto; margin-right: auto;">
        <button type="submit" name="Edit" style="margin-top: 10px;">Edit</button>
    </form>
</div>


</body>
</html>
