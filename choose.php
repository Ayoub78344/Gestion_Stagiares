<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>LINKS</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Existing CSS styling... */
        body { margin: 0; padding: 0; width: 100%; min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; background-image: url('imgs/dian10.jpg'); background-size: cover; background-position: center; transition: background-color 0.3s; } .dark-theme { background-color: #121212; color: #e0e0e0; } header { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background-color: #653332; color: #fff; position: fixed; top: 0; width: 100%; z-index: 1000; } header .parameters { display: flex; align-items: center; gap: 15px; } header .parameters a { color: #fff; font-size: 20px; text-decoration: none; transition: color 0.3s; } .parametre2, .parameters { position: absolute; top: 10px; right: 10px; display: flex; gap: 10px; align-items: center; z-index: 100; } .parametre2 a, .parameters a { color: #fff; font-size: 18px; text-decoration: none; transition: color 0.3s; } .parametre2 a:hover, .parameters a:hover { color: #ddd; } header .parameters a:hover { color: #ddd; } header .parameters .switch { display: flex; align-items: center; position: relative; width: 34px; height: 20px; } header .parameters .switch input { opacity: 0; width: 0; height: 0; } header .parameters .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; } header .parameters .slider:before { position: absolute; content: ""; height: 12px; width: 12px; border-radius: 50%; left: 4px; bottom: 4px; background-color: white; transition: .4s; } header .parameters input:checked + .slider { background-color: #653332; } header .parameters input:checked + .slider:before { transform: translateX(14px); } .container2 { margin-top: 70px; padding: 20px; background: rgba(255, 255, 255, 0.9); border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); max-width: 800px; margin-left: auto; margin-right: auto; } .container2 table { width: 100%; border-collapse: collapse; margin-top: 20px; } .container2 td, .container2 th { padding: 15px; text-align: left; color: #333; border-bottom: 1px solid #ddd; } .container2 th { background-color: #653332; color: #fff; } .container2 .bold { font-weight: bold; } .container2 .color { color: #653332; text-decoration: none; transition: color 0.3s; } .container2 .color:hover { color: #4a2d2d; } h3, h4 { margin: 20px; color: #653332; text-align: center; } @media (max-width: 768px) { header { flex-direction: column; align-items: flex-start; } header .parameters { margin-top: 10px; } .container2 { padding: 10px; } }
    </style>
</head>
<body>

<header class="header">
    <div class="parameters">
        <?php
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username']; 
            echo "<span>Welcome, $username!</span>"; 
            echo '<a href="logout.php">Logout</a>';
            echo '<label class="switch">';
            echo '<span class="slider round"></span>';
            echo '</label>';
        } else {
            echo '<a href="login.php" onclick="logout()"><i class="bx bxs-log-in-circle" ></i></a>';
        }
        ?>
    </div>
</header>

<h3><?php if(isset($_SESSION['user'])) echo 'Hello DEAR '.$_SESSION['user'] ?></h3> 
<h4>Here are the dashboard links:</h4>
<div class="container2">
    <table>
        <tr>
            <td class="bold">Choose the page:</td>
            <td class="bold">Links:</td>
        </tr>
        <tr>
            <td>The department list!! </td>
            <td><a class="color" href="department_list.php">Click Here </a></td>
        </tr>
        <tr>
            <td>The interns list!! </td>
            <td><a class="color" href="intern_list.php">Click Here </a></td>
        </tr>
        <tr>
            <td>The internship list!! </td>
            <td><a class="color" href="internship_list.php">Click Here </a></td>
        </tr>
        <tr>
            <td>The results list!! </td>
            <td><a class="color" href="results_list.php">Click Here </a></td>
        </tr>
        <tr>
            <td>The skills list!! </td>
            <td><a class="color" href="skills.php">Click Here </a></td>
        </tr>
       
    </table>
</div>    

</body>
</html>
