<?php
if (isset($_GET['id'])) {
    include("conection.php");

    // Get the ID from the query string
    $skillId = intval($_GET['id']); // Convert to integer for safety

    // Prepare the SQL statement
    $sql = "DELETE FROM skills_certifications WHERE Id_skill_cert='$skillId'";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "Skill certification deleted successfully!";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

    // Redirect to the skills list page
    header('Location: skills.php');
    exit(); // Always exit after a header redirect
} else {
    echo "No ID specified!";
}
?>
