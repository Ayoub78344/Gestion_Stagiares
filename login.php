<?php
session_start();

session_destroy();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion-stage</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image:  url('imgs/dian10.jpg');
            background-size: cover;
        }
        
        section {
            position: relative;
            width: 480px;
            padding: 60px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
        }
        
        .container {
            text-align: center;
        }
        
        .inputBox {
            position: relative;
            margin-bottom: 30px;
        }
        
        .inputBox input {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border: none;
            border-bottom: 1px solid #999;
            outline: none;
        }
        
        .inputBox input:focus {
            border-bottom: 1px solid #a80d60;
        }
        
        .rem {
            text-align: left;
            margin-bottom: 30px;
        }
        
        .inputBox1 {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            color: #fff;
            background: #653332;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            outline: none;
            transition: background 0.3s;
        }
        
        .inputBox1:hover {
            background: #653332;
        }
    </style>
    
</head>
<body>
    <section>
        <div class="color"></div>
        <div class="color"></div>
        <div class="color"></div>
        <div class="box">
           
            <div class="container">
                <div class="form">
                    <h2 >Login Form</h2>
                    
                    <form action="sessioincookies.php" method="POST">
                        <?php if (isset( $_GET['error'])) echo $_GET['error']  ?>
                        <div class="inputBox">
                            <input type="text" name="username" placeholder="Username" 
                            value="<?php if (isset($_COOKIE['user'])) { echo $_COOKIE['user']; } ?>">
                        </div>
                        <div class="inputBox">
                            <input type="password" name="password" placeholder="Password" 
                            value="<?php if (isset($_COOKIE['pass'])) { echo $_COOKIE['pass']; } ?>">
                        </div>
                        <div class="rem">
                        <input type="checkbox" name="check" id="remember me" ><label class="remb">Remember me</label>
                         </div>
                        
                        <div class="inputBox" >
                            <input type="submit" value="Login" class="inputBox1" >
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script type='text/javascript'>
    

</script>
</body>


</html>