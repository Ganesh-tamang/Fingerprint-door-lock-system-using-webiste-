<?php 
    require_once './model/database_function.php';
    $database = new QueryClass;
    $users_name = $database->get_all_users();

    session_start(); 
    if(isset($_POST['password']) && isset($_POST['username'])){
        unset($_SESSION["username"]);
        $_SESSION['username']= $_POST['username'];
        foreach($users_name as $user){
            if(trim($_POST['username'])==$user['user_name']){
                if($_POST['password']!=$user['password']){
                    $_SESSION['password_error']='The username, or password you entered is incorrect. Please try again.';
                    header('location:./login.php');
                    return;
                }else{
                    unset($_SESSION["account"]);
                    $_SESSION['success']="login";
                    $_SESSION['account']=$_POST['username'];
                    header('location:./home.php');
                    return;
                }
            }
          
        }
        $_SESSION['password_error']='The username, or password you entered is incorrect. Please try again.';
        header('location:./login.php');
        return;
       
    }
    
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login </title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <header class="header">
            <div class="header-container">
                
                <div class="header_title">
                    <h1>Minor project </h1>
                </div> 
                <nav class="nav">
                    <ul>
                        <li><a href="./home.php" >Home</a></li>
                        <li><a href="./login.php" class="actives">Login</a></li>
                        <li><a href="./register.php" >Register</a></li>
                        
                    </ul>
                </nav>

            </div>
        </header>

        <div class="message">
                        <?php
                            if(isset($_SESSION['user_created_message'])){
                                echo 'User '.$_SESSION['user_created_message']." is successfully registered. Please! login";
                                unset($_SESSION['user_created_message']);
                            }
                        ?>
        </div>

    <section class="flex">
        <img src="./access/image/fingerprint.jpg" alt="fingerprint" class="cover_image">         
            <div class="form_contain">

                <form class="form" method='post' id ="myForm">
                      <?php 
                        if(isset($_SESSION['password_error'])){
                            echo '<p style="color:red;font-size=0.5rem;">'.$_SESSION['password_error'].'</p>';
                            unset($_SESSION['password_error']);
                        }
                    ?>
                    <label for="name">Username</label>
                    <input type="text" name="username" id="name" 
                    <?php if(isset($_SESSION['username'])){
                        echo " value= ".$_SESSION['username'];
                        unset($_SESSION['username']);
                        } ?> required>
                    <label for="Password">Password</label>
                    <input type="password" name="password" id="password" required>
                    
                    <input class="submit_button" type="submit" value="Submit">
                </form> 
                <p>don't have an account?<a href="./register.php"> Register Now</a> </p> 
            </div>
           
    </section>
    

</body>
</html>