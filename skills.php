<?php
include('conection.php');
session_start();

// Check if the user is logged in
if (isset($_SESSION['user']) && isset($_SESSION['pass'])) {
    if (empty($_SESSION['user']) || empty($_SESSION['pass'])) {
        header('Location: login.php?error=username and password invalid');
        exit();
    } else {
        // Fetch the administration details using a prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM administration WHERE User_Name_adminn = ?");
        $stmt->bind_param("s", $_SESSION['user']);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            header('Location: login.php?error=database error');
            exit();
        }

        $row = $result->fetch_assoc();

        // Validate password
        if ($row && $row['Password_adminn'] === $_SESSION['pass']) {
            // Fetch skills certifications data
            $stmt = $conn->prepare("
                SELECT 
                    sc.*, 
                    i.First_name_intern, 
                    i.Last_name_intern, 
                    d.Name_depart 
                FROM 
                    skills_certifications sc
                JOIN 
                    intern i ON sc.Id_intern = i.Id_intern
                JOIN 
                    department d ON sc.Id_depart = d.Id_depart
                WHERE 
                    sc.Id_skill_cert IS NOT NULL
            ");
            $stmt->execute();
            $queryResult = $stmt->get_result();

            if (!$queryResult) {
                header('Location: login.php?error=database error');
                exit();
            }
        } else {
            header('Location: login.php?error=password invalid');
            exit();
        }
    }
} else {
    header('Location: login.php?error=username and password invalid');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills Certifications List</title>
    <link rel="stylesheet" href="skills.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <script type="text/javascript">
        function chkalert(id) {
            var confirmDelete = confirm("Are you sure you want to remove this certification?");
            if (confirmDelete) {
                window.location.href = "delete_skills.php?id=" + id;
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
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
    </div>
</header>

<div class="container">
    <h2>Hello DEAR <?php echo htmlspecialchars($_SESSION['user']); ?></h2>
    <h1>Here's the list of skills certifications:</h1>

    <a href="Add_skills.php?id=<?php echo htmlspecialchars($row['Id_adminn']); ?>" class="btn-primary">Add</a>

    <table class="table table-hover text-center">
    <thead class="table-dark">
        <tr>
            <th>Intern First Name</th>
            <th>Intern Last Name</th>
            <th>Department Name</th>
            <th>Skills Acquired</th>
            <th>Projects</th>
            <th>Mentor Feedback</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($queryResult && mysqli_num_rows($queryResult) > 0): ?>
            <?php while ($line = mysqli_fetch_assoc($queryResult)): ?>
            <tr>
                <td><?php echo htmlspecialchars($line['First_name_intern']); ?></td>
                <td><?php echo htmlspecialchars($line['Last_name_intern']); ?></td>
                <td><?php echo htmlspecialchars($line['Name_depart']); ?></td>
                <td><?php echo htmlspecialchars($line['Skills_acquired']); ?></td>
                <td><?php echo htmlspecialchars($line['Projects']); ?></td>
                <td><?php echo htmlspecialchars($line['Mentor_feedback']); ?></td>
                <td>
                    <a href="edit_skills.php?id=<?php echo $line['Id_skill_cert']; ?>"><i class="fas fa-edit edit-icon"></i></a>
                </td>
                <td>
                    <a href="javascript:void(0);" onclick="chkalert(<?php echo $line['Id_skill_cert']; ?>)"><i class="fas fa-trash-alt delete-icon"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">No skills certifications found</td></tr>
        <?php endif; ?>
    </tbody>
</table>



</div>

</body>
</html>
