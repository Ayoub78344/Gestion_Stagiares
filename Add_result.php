<?php
include('conection.php');
session_start();

if (empty($_SESSION['user'])) {
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
    $notes = $_POST['notes'] ?? '';
    $mention = $_POST['mention'] ?? '';

    if ($department && $intern && $notes && $mention) {
        $department = mysqli_real_escape_string($conn, $department);
        $intern = mysqli_real_escape_string($conn, $intern);
        $notes = mysqli_real_escape_string($conn, $notes);
        $mention = mysqli_real_escape_string($conn, $mention);

        // Prepared statement to check if the department and intern exist
        $dept_check = $conn->prepare("SELECT 1 FROM department WHERE Id_depart = ? AND Id_admin = ?");
        $dept_check->bind_param('ii', $department, $adminId);
        $dept_check->execute();
        $dept_check_result = $dept_check->get_result();

        $intern_check = $conn->prepare("SELECT 1 FROM intern WHERE Id_intern = ? AND Id_admin = ?");
        $intern_check->bind_param('ii', $intern, $adminId);
        $intern_check->execute();
        $intern_check_result = $intern_check->get_result();

        if ($dept_check_result->num_rows > 0 && $intern_check_result->num_rows > 0) {
            $sql_insert = "INSERT INTO results (Id_admin, Id_depart, Id_intern, Notes, Mention) 
                           VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param('iiiss', $adminId, $department, $intern, $notes, $mention);

            if ($stmt->execute()) {
                header('Location: results_list.php');
                exit();
            } else {
                die("Error: " . $stmt->error);
            }
        } else {
            echo "Invalid department or intern.";
        }
    } else {
        echo "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Result</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="Add_results.css">
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

<div class="container">
    <form method="POST" id="resultForm">
        <div class="form-group">
            <label for="department">
                <i class="fas fa-building icon"></i>
                Choose the department:
            </label>
            <select name="department" id="department">
                <option value="" disabled selected>Choose a department...</option>
                <?php while ($department = mysqli_fetch_assoc($departments)) { ?>
                    <option value="<?php echo htmlspecialchars($department['Id_depart']); ?>">
                        <?php echo htmlspecialchars($department['Name_depart']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="intern">
                <i class="fas fa-user icon"></i>
                Choose the intern:
            </label>
            <select name="intern" id="intern">
                <option value="" disabled selected>Choose an intern...</option>
                <?php while ($intern = mysqli_fetch_assoc($interns)) { ?>
                    <option value="<?php echo htmlspecialchars($intern['Id_intern']); ?>">
                        <?php echo htmlspecialchars($intern['First_name_intern'] . ' ' . $intern['Last_name_intern']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="notes">
                <i class="fas fa-pencil-alt icon"></i>
                Notes:
            </label>
            <input type="text" name="notes" placeholder="NOTE...">
        </div>

        <div class="form-group">
            <label for="mention">
                <i class="fas fa-star icon"></i>
                Mention:
            </label>
            <select name="mention" id="mention">
                <option value="Choose">Choose</option>
                <option value="Satisfaisant">Satisfaisant</option>
                <option value="Bien">Bien</option>
                <option value="Très Bien">Très Bien</option>
                <option value="Excellent">Excellent</option>
            </select>
        </div>

        <input class="sun" type="submit" name="Submit" value="Submit">
    </form>
</div>

</body>
</html>
