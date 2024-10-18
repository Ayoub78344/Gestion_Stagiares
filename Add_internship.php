<?php
include('conection.php');
session_start();

if (empty($_SESSION['user']) || empty($_SESSION['pass'])) {
    header('Location: login.php?error=username and password invalid');
    exit();
}

$user = $_SESSION['user'];
$sql = "SELECT Id_adminn FROM administration WHERE User_Name_adminn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$adminId = $row['Id_adminn'];

$departments = $conn->query("SELECT * FROM department WHERE Id_admin = '$adminId'");
$interns = $conn->query("SELECT * FROM intern WHERE Id_admin = '$adminId'");

if (isset($_POST['Submit'])) {
    $department = $_POST['department'] ?? '';
    $intern = $_POST['intern'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    if ($department && $intern && $start_date && $end_date && validateDate($start_date) && validateDate($end_date)) {
        $department = mysqli_real_escape_string($conn, $department);
        $intern = mysqli_real_escape_string($conn, $intern);

        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM department WHERE Id_depart = '$department'")) &&
            mysqli_num_rows(mysqli_query($conn, "SELECT * FROM intern WHERE Id_intern = '$intern'"))) {

            $sql_insert = "INSERT INTO internship (Id_admin, Id_depart, Id_intern, Start_date, End_date) 
                           VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param('iisss', $adminId, $department, $intern, $start_date, $end_date);

            if ($stmt->execute()) {
                header('Location: internship_list.php');
                exit();
            } else {
                die("Error: " . $stmt->error);
            }
        } else {
            echo "Invalid department or intern.";
        }
    } else {
        echo "All fields are required and dates must be valid.";
    }
}

function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Internship</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="Add_intership.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="logo">
        <a href="#">
            <img src="imgs/output9.png" alt="Logo">
        </a>
    </div>
    <div class="parametre2">
        <a href="department_list.php"><i class="fas fa-th-list"></i></a>
        <a href="intern_list.php"><i class="fas fa-users"></i></a>
        <a href="internship_list.php"><i class="fas fa-building"></i></a>
        <a href="results_list.php"><i class="fas fa-chart-pie"></i></a>
        <a href="skills.php"><i class="fas fa-award"></i></a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
        <?php else: ?>
            <a href="login.php"><i class="fas fa-sign-in-alt"></i></a>
        <?php endif; ?>
    </div>
</header>

<form method="POST" id="internshipForm" class="container">
    <h1 style="color: black;">Assign Internship</h1>

    <div class="form-group">
        <div class="form-title" style="display: flex; align-items: center;">
            <i class="fas fa-building" style="margin-right: 8px;"></i>
            <span>Choose the department:</span>
        </div>
        <select name="department" id="department" style="margin-top: 10px;">
            <option value="" disabled selected>Choose a department...</option>
            <?php while ($department = mysqli_fetch_assoc($departments)) { ?>
                <option value="<?php echo htmlspecialchars($department['Id_depart']); ?>"><?php echo htmlspecialchars($department['Name_depart']); ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <div class="form-title" style="display: flex; align-items: center;">
            <i class="fas fa-user" style="margin-right: 8px;"></i>
            <span>Choose the intern:</span>
        </div>
        <select name="intern" id="intern" style="margin-top: 10px;">
            <option value="" disabled selected>Choose an intern...</option>
            <?php while ($intern = mysqli_fetch_assoc($interns)) { ?>
                <option value="<?php echo htmlspecialchars($intern['Id_intern']); ?>"><?php echo htmlspecialchars($intern['First_name_intern'] . ' ' . $intern['Last_name_intern']); ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <div class="form-title" style="display: flex; align-items: center;">
            <i class="fas fa-calendar" style="margin-right: 8px;"></i>
            <span>Start Date:</span>
        </div>
        <input type="date" name="start_date" required style="margin-top: 10px;">
    </div>

    <div class="form-group">
        <div class="form-title" style="display: flex; align-items: center;">
            <i class="fas fa-calendar" style="margin-right: 8px;"></i>
            <span>End Date:</span>
        </div>
        <input type="date" name="end_date" required style="margin-top: 10px;">
    </div>

    <input type="submit" name="Submit" value="Submit">
</form>

</body>
</html>
