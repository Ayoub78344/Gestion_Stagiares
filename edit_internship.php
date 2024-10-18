<?php
// Inclure le fichier de connexion à la base de données
include('conection.php');

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $internship_id = $_POST['internship_id'];
    $department_id = $_POST['department'];
    $intern_id = $_POST['intern'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Utiliser des requêtes préparées pour éviter les injections SQL
    $stmt = $conn->prepare("UPDATE internship SET Id_depart=?, Id_intern=?, Start_date=?, End_date=? WHERE Id_Internship=?");
    $stmt->bind_param("iisss", $department_id, $intern_id, $start_date, $end_date, $internship_id);

    if ($stmt->execute()) {
        // Redirection vers une page après la mise à jour
        header('Location: internship_list.php');
        exit();
    } else {
        echo "Erreur lors de la mise à jour: " . $stmt->error;
    }

    $stmt->close();
}

// Récupérer l'ID du stage (internship) à modifier depuis l'URL
if (isset($_GET['id'])) {
    $internship_id = $_GET['id'];

    // Récupérer les informations du stage depuis la base de données
    $stmt = $conn->prepare("SELECT * FROM internship WHERE Id_Internship=?");
    $stmt->bind_param("i", $internship_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Récupérer les informations actuelles du stage
    $department_id = $row['Id_depart'];
    $intern_id = $row['Id_intern'];
    $start_date = $row['Start_date'];
    $end_date = $row['End_date'];

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Internship</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="edit_interships.css">
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

    
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="container">
    <input type="hidden" name="internship_id" value="<?php echo htmlspecialchars($internship_id); ?>">
    <h1>Edit Internship:</h1>

    <div class="form-group">
        <label for="department">
            <i class="fas fa-building" style="margin-right: 5px; color: black;"></i>
            Department name:
        </label>
        <select id="department" name="department">
            <?php
            $sql = "SELECT * FROM department";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $selected = ($row['Id_depart'] == $department_id) ? "selected" : "";
                echo "<option value='" . htmlspecialchars($row['Id_depart']) . "' $selected>" . htmlspecialchars($row['Name_depart']) . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="intern">
            <i class="fas fa-user" style="margin-right: 5px; color: black;"></i>
            Intern information:
        </label>
        <select id="intern" name="intern">
            <?php
            $sql = "SELECT * FROM intern";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $full_name = htmlspecialchars($row['First_name_intern']) . " " . htmlspecialchars($row['Last_name_intern']);
                $selected = ($row['Id_intern'] == $intern_id) ? "selected" : "";
                echo "<option value='" . htmlspecialchars($row['Id_intern']) . "' $selected>$full_name</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="start_date">
            <i class="fas fa-calendar" style="margin-right: 5px; color: black;"></i>
            Start Date:
        </label>
        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
    </div>

    <div class="form-group">
        <label for="end_date">
            <i class="fas fa-calendar" style="margin-right: 5px; color: black;"></i>
            End Date:
        </label>
        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
    </div>

    <input type="submit" value="Edit">
</form>

 
</body>
</html>
