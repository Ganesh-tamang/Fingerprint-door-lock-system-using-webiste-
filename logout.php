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
    <title>Logout</title>
    <link rel="stylesheet" href="./css/header.css">
</head>
<body style="background-color: black;color: antiquewhite;font-size: 1.3rem;">
    <header class="header">
        <div class="header-container">
            
            <div class="header_title">
                <h1>Minor project </h1>
            </div> 
            <nav class="nav">
                <ul>
                <li><a href="./register.php" >Register</a></li>
                    <li><a href="./login.php" >Login</a></li>
                    <li><a href="./logout.php" class="actives">Logout</a></li>                    
                </ul>
            </nav>
        </div>
    </header>
    <div style="color:green;margin: 0 30%; font-size:2rem">You have successfully logout!!</div>
    <section style="padding:10px;background-color:cyan; color:black; border-radius:5000px;" >
        
        <h2 style="margin:40px">Do you want to login ! <a href="./login.php"  style=" text-decoration: none; color:orange;background-color: green; padding:10px; border-radius: 5000px;">Login</a></h2>
        
    </section>
</body>
</html>