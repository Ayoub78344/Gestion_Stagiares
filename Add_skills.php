<?php
include('conection.php');
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['pass'])) {
    header('Location: login.php?error=username and password invalid');
    exit();
}

$user = $_SESSION['user'];

// Secure fetching of admin ID
$sql = "SELECT Id_adminn FROM administration WHERE User_Name_adminn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Admin not found.");
}

$adminId = $row['Id_adminn'];

// Fetching the interns and departments securely
$interns = $conn->query("SELECT * FROM intern WHERE Id_admin = '$adminId'");
$departments = $conn->query("SELECT * FROM department WHERE Id_admin = '$adminId'");
$skills = $conn->query("SELECT * FROM skills_certifications");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $internId = $_POST['intern'] ?? '';
    $departmentId = $_POST['department'] ?? '';
    $skillsAcquired = $_POST['skills_acquired'] ?? '';
    $projects = $_POST['projects'] ?? '';
    $mentorFeedback = $_POST['mentor_feedback'] ?? '';

    // Validate that all fields are filled
    if ($internId && $departmentId && $skillsAcquired && $projects && $mentorFeedback) {
        $internId = mysqli_real_escape_string($conn, $internId);
        $departmentId = mysqli_real_escape_string($conn, $departmentId);
        $skillsAcquired = mysqli_real_escape_string($conn, $skillsAcquired);
        $projects = mysqli_real_escape_string($conn, $projects);
        $mentorFeedback = mysqli_real_escape_string($conn, $mentorFeedback);

        // Validate intern and department exist
        $internQuery = $conn->prepare("SELECT * FROM intern WHERE Id_intern = ?");
        $internQuery->bind_param('i', $internId);
        $internQuery->execute();
        $internResult = $internQuery->get_result();

        $departmentQuery = $conn->prepare("SELECT * FROM department WHERE Id_depart = ?");
        $departmentQuery->bind_param('i', $departmentId);
        $departmentQuery->execute();
        $departmentResult = $departmentQuery->get_result();

        if ($internResult->num_rows > 0 && $departmentResult->num_rows > 0) {
            // Include Id_admin in the insert query
            $sql_insert = "INSERT INTO skills_certifications (Id_admin, Id_intern, Id_depart, Skills_acquired, Projects, Mentor_feedback) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param('iiisss', $adminId, $internId, $departmentId, $skillsAcquired, $projects, $mentorFeedback);

            if ($stmt->execute()) {
                header('Location: skills.php');
                exit();
            } else {
                die("Error executing query: " . $stmt->error);
            }
        } else {
            echo "Invalid intern or department.";
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
    <title>Add Skills Certifications</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="Add_skills.css">
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
<br><b><br><br><br></b>
<form method="POST" id="skillsCertificationForm" class="container">
    <div class="form-group">
        <label for="intern">
            <i class="fas fa-user icon"></i>
            Choose the intern:
        </label>
        <select name="intern" id="intern" required>
            <option value="" disabled selected>Choose an intern...</option>
            <?php while ($intern = mysqli_fetch_assoc($interns)) { ?>
                <option value="<?php echo htmlspecialchars($intern['Id_intern']); ?>">
                    <?php echo htmlspecialchars($intern['First_name_intern'] . ' ' . $intern['Last_name_intern']); ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label for="department">
            <i class="fas fa-building icon"></i>
            Choose the department:
        </label>
        <select name="department" id="department" required>
            <option value="" disabled selected>Choose a department...</option>
            <?php while ($department = mysqli_fetch_assoc($departments)) { ?>
                <option value="<?php echo htmlspecialchars($department['Id_depart']); ?>">
                    <?php echo htmlspecialchars($department['Name_depart']); ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label for="skills_acquired">
            <i class="fas fa-star icon"></i>
            Skills Acquired:
        </label>
        <select name="skills_acquired" id="skills_acquired" required>
            <option value="" disabled selected>Select skills...</option>
            <option value="Communication">Communication</option>
            <option value="Teamwork">Teamwork</option>
            <option value="Problem Solving">Problem Solving</option>
            <option value="Time Management">Time Management</option>
            <option value="Leadership">Leadership</option>
            <option value="Adaptability">Adaptability</option>
            <option value="Critical Thinking">Critical Thinking</option>
            <option value="Technical Skills">Technical Skills</option>
            <option value="Creativity">Creativity</option>
            <option value="Research">Research</option>
        </select>
    </div>

    <div class="form-group">
        <label for="projects">
            <i class="fas fa-project-diagram icon"></i>
            Projects:
        </label>
        <input type="text" name="projects" required>
    </div>

    <div class="form-group">
        <label for="mentor_feedback">
            <i class="fas fa-comments icon"></i>
            Mentor Feedback:
        </label>
        <textarea name="mentor_feedback" required></textarea>
    </div>

    <input class="sun" type="submit" name="Submit" value="Submit">
</form>


</body>
</html>
