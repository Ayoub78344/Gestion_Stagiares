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
            // Fetch the results related to the admin with prepared statements
            $stmt = $conn->prepare("SELECT results.*, department.Name_depart, intern.First_name_intern, intern.Last_name_intern 
                                    FROM results 
                                    INNER JOIN department ON results.Id_depart = department.Id_depart 
                                    INNER JOIN intern ON results.Id_intern = intern.Id_intern 
                                    WHERE results.Id_admin = ?");
            $stmt->bind_param("i", $row['Id_adminn']);
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
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Results List</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="results_list.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
 
    <script type='text/javascript'>
        function chkalert(id) {
            var confirmDelete = confirm("Are you sure you want to remove this result?");
            if (confirmDelete) {
                window.location.href = "delete_result.php?id=" + id;
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
    <div class="parametre2">
        <a href="department_list.php"><i class="fas fa-th-list"></i></a> <!-- General list icon -->
        <a href="intern_list.php"><i class="fas fa-users"></i></a> <!-- Group icon -->
        <a href="internship_list.php"><i class="fas fa-building"></i></a> <!-- Building icon -->
        <a href="results_list.php" class="results-icon"><i class="fas fa-chart-pie"></i></a>
        <a href="skills.php"><i class="fas fa-award"></i></a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="logout.php" class="logout-icon"><i class="fas fa-sign-out-alt"></i></a> <!-- Logout icon -->
        <?php else: ?>
            <a href="login.php"><i class="fas fa-sign-in-alt"></i></a> <!-- Login icon -->
        <?php endif; ?>
    </div>
</header>

    <div class="content-wrapper">
        <div class="container">
            <h2><?php if (isset($_SESSION['user'])) echo 'Hello DEAR ' . htmlspecialchars($_SESSION['user']); ?></h2>
            <h1>Here's the list of the results:</h1>
            <a href="Add_result.php?id=<?php echo htmlspecialchars($row['Id_adminn']); ?>" class="btn btn-primary">Add</a>
            <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Department Name:</th>
                    <th>First Name:</th>
                    <th>Last Name:</th>
                    <th>Notes:</th>
                    <th>Mention:</th>
                    <th>Edit:</th>
                    <th>Delete:</th>

                </tr>
            </thead>

            <tbody>
                <?php while ($line = $queryResult->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($line['Name_depart']); ?></td>
                        <td><?php echo htmlspecialchars($line['First_name_intern']); ?></td>
                        <td><?php echo htmlspecialchars($line['Last_name_intern']); ?></td>
                        <td><?php echo htmlspecialchars($line['Notes']); ?></td>
                        <td><?php echo htmlspecialchars($line['Mention']); ?></td>
                        <td><a href="edit_result.php?id=<?php echo htmlspecialchars($line['Id_Result']); ?>"><i class="fas fa-edit edit-icon"></i></a></td>
                        <td><a href="javascript:void(0);" onclick="chkalert(<?php echo htmlspecialchars($line['Id_Result']); ?>)"><i class="fas fa-trash-alt delete-icon"></i></a></td>
                    </tr>
                <?php } ?>
            </tbody>
            </table>
        </div>
    </div>
</body>
</html>
