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

// Fetching the skill certification details based on the provided ID
$skillId = $_GET['id'] ?? '';
$skillData = null;

if ($skillId) {
    $stmt = $conn->prepare("SELECT * FROM skills_certifications WHERE Id_skill_cert = ? AND Id_admin = ?");
    $stmt->bind_param('ii', $skillId, $adminId);
    $stmt->execute();
    $skillData = $stmt->get_result()->fetch_assoc();
}

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

        // Update the skills_certifications record
        $sql_update = "UPDATE skills_certifications 
                       SET Id_intern = ?, Id_depart = ?, Skills_acquired = ?, Projects = ?, Mentor_feedback = ? 
                       WHERE Id_skill_cert = ? AND Id_admin = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param('iisssii', $internId, $departmentId, $skillsAcquired, $projects, $mentorFeedback, $skillId, $adminId);

        if ($stmt->execute()) {
            header('Location: skills.php');
            exit();
        } else {
            die("Error executing query: " . $stmt->error);
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
    <title>Edit Skills Certifications</title>
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

<form method="POST" id="skillsCertificationForm" class="container">

    <label for="intern">
        <i class="fas fa-user" style="margin-right: 5px;"></i>
        Choose the intern: 
    </label>
    <select name="intern" id="intern" required>
        <option value="" disabled>Select an intern...</option>
        <?php
        $interns = $conn->query("SELECT * FROM intern WHERE id_admin = '$adminId'");
        while ($intern = mysqli_fetch_assoc($interns)) {
            $selected = $skillData && $skillData['Id_intern'] == $intern['Id_intern'] ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($intern['Id_intern']) . '" ' . $selected . '>' . htmlspecialchars($intern['First_name_intern'] . ' ' . $intern['Last_name_intern']) . '</option>';
        }
        ?>
    </select>

    <label for="department">
        <i class="fas fa-building" style="margin-right: 5px;"></i>
        Choose the department: 
    </label>
    <select name="department" id="department" required>
        <option value="" disabled>Select a department...</option>
        <?php
        $departments = $conn->query("SELECT * FROM department WHERE Id_admin = '$adminId'");
        while ($department = mysqli_fetch_assoc($departments)) {
            $selected = $skillData && $skillData['Id_depart'] == $department['Id_depart'] ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($department['Id_depart']) . '" ' . $selected . '>' . htmlspecialchars($department['Name_depart']) . '</option>';
        }
        ?>
    </select>

    <label for="skills_acquired">
        <i class="fas fa-lightbulb" style="margin-right: 5px;"></i>
        Skills Acquired: 
    </label>
    <select name="skills_acquired" id="skills_acquired" required>
        <option value="" disabled>Select skills...</option>
        <option value="Communication" <?php echo ($skillData && $skillData['Skills_acquired'] == 'Communication') ? 'selected' : ''; ?>>Communication</option>
        <option value="Teamwork" <?php echo ($skillData && $skillData['Skills_acquired'] == 'Teamwork') ? 'selected' : ''; ?>>Teamwork</option>
        <option value="Problem Solving" <?php echo ($skillData && $skillData['Skills_acquired'] == 'Problem Solving') ? 'selected' : ''; ?>>Problem Solving</option>
        <option value="Time Management" <?php echo ($skillData && $skillData['Skills_acquired'] == 'Time Management') ? 'selected' : ''; ?>>Time Management</option>
        <option value="Leadership" <?php echo ($skillData && $skillData['Skills_acquired'] == 'Leadership') ? 'selected' : ''; ?>>Leadership</option>
        <option value="Adaptability" <?php echo ($skillData && $skillData['Skills_acquired'] == 'Adaptability') ? 'selected' : ''; ?>>Adaptability</option>
        <option value="Critical Thinking" <?php echo ($skillData && $skillData['Skills_acquired'] == 'Critical Thinking') ? 'selected' : ''; ?>>Critical Thinking</option>
        <option value="Technical Skills" <?php echo ($skillData && $skillData['Skills_acquired'] == 'Technical Skills') ? 'selected' : ''; ?>>Technical Skills</option>
        <option value="Creativity" <?php echo ($skillData && $skillData['Skills_acquired'] == 'Creativity') ? 'selected' : ''; ?>>Creativity</option>
        <option value="Research" <?php echo ($skillData && $skillData['Skills_acquired'] == 'Research') ? 'selected' : ''; ?>>Research</option>
    </select>

    <label for="projects">
        <i class="fas fa-folder" style="margin-right: 5px;"></i>
        Projects: 
    </label>
    <input type="text" name="projects" value="<?php echo htmlspecialchars($skillData['Projects']); ?>" required>

    <label for="mentor_feedback">
        <i class="fas fa-comments" style="margin-right: 5px;"></i>
        Mentor Feedback: 
    </label>
    <textarea name="mentor_feedback" required><?php echo htmlspecialchars($skillData['Mentor_feedback']); ?></textarea>

    <br><br>

    <input type="submit" name="Submit" value="Update">
</form>




</body>
</html>
