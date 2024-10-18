<?php
include('conection.php'); 
session_start(); 

if (isset($_POST['Edit'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['new_first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['new_last_name']);
    $birthday = mysqli_real_escape_string($conn, $_POST['new_birthday']);
    $niveau = mysqli_real_escape_string($conn, $_POST['Niveau']);
    $id = mysqli_real_escape_string($conn, $_GET['id']); 

    if (isset($first_name, $last_name, $birthday, $id)) {
        // Prepare and bind parameters, correcting the number of params with 'ssssi'
        $stmt = $conn->prepare("UPDATE intern SET First_name_intern = ?, Last_name_intern = ?, Birthday_intern = ?, Niveau = ? WHERE Id_intern = ?");
        $stmt->bind_param("ssssi", $first_name, $last_name, $birthday, $niveau, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header('Location: intern_list.php');
            exit(); 
        } else {
            echo "No changes were made or an error occurred.";
        }
        $stmt->close();
    }
}

// Fetch the current intern data
$id = mysqli_real_escape_string($conn, $_GET['id']);
$stmt = $conn->prepare("SELECT First_name_intern, Last_name_intern, Birthday_intern, Niveau FROM intern WHERE Id_intern = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $current_first_name = htmlspecialchars($row['First_name_intern']);
    $current_last_name = htmlspecialchars($row['Last_name_intern']);
    $current_birthday = htmlspecialchars($row['Birthday_intern']);
    $current_niveau = htmlspecialchars($row['Niveau']); // Fetch Niveau
} else {
    echo "Intern not found.";
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Edit Intern</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="edit_intern.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css' rel="stylesheet">

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
    <h1 style="color: black;">Edit the Information of the Intern</h1>
    <form method="POST">
        <div class="form-group" style="display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-user" style="margin-right: 5px; color: black;"></i>
            <label for="first_name" style="margin: 0; color: black;">First Name:</label>
        </div>
        <input type="text" id="first_name" name="new_first_name" placeholder="Change First Name..." value="<?php echo htmlspecialchars($current_first_name); ?>" style="margin: 10px auto; display: block;">

        <div class="form-group" style="display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-user" style="margin-right: 5px; color: black;"></i>
            <label for="last_name" style="margin: 0; color: black;">Last Name:</label>
        </div>
        <input type="text" id="last_name" name="new_last_name" placeholder="Change Last Name..." value="<?php echo htmlspecialchars($current_last_name); ?>" style="margin: 10px auto; display: block;">

        <div class="form-group" style="display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-calendar" style="margin-right: 5px; color: black;"></i>
            <label for="birthday" style="margin: 0; color: black;">Birthday:</label>
        </div>
        <input type="date" id="birthday" name="new_birthday" value="<?php echo htmlspecialchars($current_birthday); ?>" style="margin: 10px auto; display: block;">

        <div class="form-group" style="display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-graduation-cap" style="margin-right: 5px; color: black;"></i>
            <label for="Niveau" style="margin: 0; color: black;">Niveau:</label>
        </div>
        <input type="text" id="Niveau" name="Niveau" value="<?php echo htmlspecialchars($current_niveau); ?>" style="margin: 10px auto; display: block;">
        
        <button type="submit" name="Edit" style="margin-top: 10px;">Edit</button>
    </form>
</div>

</body>
</html>
