<?php
// Include the database connection file
include('conection.php');
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $result_id = $_POST['result_id'];
    $department_id = $_POST['department'];
    $intern_id = $_POST['intern'];
    $notes = $_POST['notes'];
    $mention = $_POST['mention'];

    // Ensure all fields are filled
    if (!empty($result_id) && !empty($department_id) && !empty($intern_id) && !empty($notes) && !empty($mention)) {
        // Use prepared statements to avoid SQL injection
        $stmt = $conn->prepare("UPDATE results SET Id_depart=?, Id_intern=?, Notes=?, Mention=? WHERE Id_Result=?");
        $stmt->bind_param("iissi", $department_id, $intern_id, $notes, $mention, $result_id);

        if ($stmt->execute()) {
            // Redirect to the results list page after updating
            header('Location: results_list.php');
            exit();
        } else {
            echo "Error updating result: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}

// Retrieve the result ID to edit from the URL
if (isset($_GET['id'])) {
    $result_id = $_GET['id'];

    // Fetch the result details from the database
    $stmt = $conn->prepare("SELECT * FROM results WHERE Id_Result=?");
    $stmt->bind_param("i", $result_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Get current result details
    $department_id = $row['Id_depart'];
    $intern_id = $row['Id_intern'];
    $notes = $row['Notes'];
    $mention = $row['Mention'];

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Result</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="edit_results.css">
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
 

<main>
    
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="container">
    <input type="hidden" name="result_id" value="<?php echo htmlspecialchars($result_id); ?>">
    <h1>Edit Result :</h1>
    
    <div class="form-group">
        <label for="department">
            <i class="fas fa-building icon"></i>
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
            <i class="fas fa-user icon"></i>
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
        <label for="notes">
            <i class="fas fa-pencil-alt icon"></i>
            Notes:
        </label>
        <input type="text" id="notes" name="notes" value="<?php echo htmlspecialchars($notes); ?>">
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

    <input type="submit" value="Edit">
</form>

</main>
</body>
</html>
