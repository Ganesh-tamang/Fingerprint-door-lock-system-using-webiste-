<?php 
    session_start();
    require_once './model/database_function.php';
    $database = new QueryClass;
    $last_userid = $database->get_lastuser_id();
    $users_name = $database->get_all_users();  
    
    if(isset($_POST['username'])){       
        unset($_SESSION["username"]);
        $_SESSION['username']= $_POST['username'];
            
            if(strlen($_POST['password1']) < 8){
                $_SESSION['password_error']='password should be atleast of 8 characters';
                header('location:./register.php');
                return;
            }
            if($_POST['password1']!=$_POST['password2']){
                $_SESSION['password_error']='password didnot match';
                header('location:./register.php');
                return;
            } 
            if($_POST['pincode']!='1234'){
                $_SESSION['registration_code_error']='invalid registration code';
                header('location:./register.php');
                return;
            } 
            //checking user already exists or not 
            foreach($users_name as $user){
                if($_POST['username']==$user['user_name']){
                    $_SESSION['username_error']='username already exists';
                    header('location:./register.php');
                    return;
                }               
              
            }
            $counter = "activated";
            $database->insert_message("Place finger in fingerprint sensor");
            $_SESSION['user_created_message']= $_POST['username'];//used later in login php to indicate successful          
    }  
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/register.css">
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
                        <li><a href="./login.php" >Login</a></li>
                        <li><a href="./register.php" class="actives">Register</a></li>
                        
                    </ul>
                </nav>
            </div>
        </header>

    <section class="flex">
        <img src="./access/image/fingerprint.jpg" alt="fingerprint" class="cover_image">         
            <div class="form_contain">
                <form class="form" method='post' id ="myForm">
                    <?php 
                            if(isset($_SESSION['username_error'])){
                                echo '<p style="color:red; font-size=0.3rem;">'.$_SESSION['username_error'].'</p>';
                                unset($_SESSION['username_error']);
                            }
                        ?>    
                    <label for="name">Username</label>
                    <input type="text" name="username" id="name"  <?php if(isset($_SESSION['username'])){
                        echo " value= ".$_SESSION['username'];
                        unset($_SESSION['username']);
                        } ?> required>
                    <label for="Password1">Password</label>
                    <input type="password" name="password1" id="password1" required>
                    <?php 
                        if(isset($_SESSION['password_error'])){
                            echo '<p style="color:red;font-size=0.3rem;">'.$_SESSION['password_error'].'</p>';
                            unset($_SESSION['password_error']);
                        }
                    ?>
                    <label for="Password2">Same Password</label>
                    <input type="password" name="password2" id="password2" required>
                    <?php 
                        if(isset($_SESSION['registration_code_error'])){
                            echo '<p style="color:red;font-size=0.3rem;">'.$_SESSION['registration_code_error'].'</p>';
                            unset($_SESSION['registration_code_error']);
                        }
                    ?>
                    <label for="pincode">Registration Code</label>
                    <input type="text" name="pincode" id="pincode" required>
                    <input class="submit_button" type="submit" value="Submit">
                </form> 
                <p>Already have an account?<a href="./login.php"> Login</a></p> 
            </div>     
    </section>
    
    <div class="message_container invisible">
    <div class="message_box">
        <h2 class="message_h2"><?php if(isset($_POST['username'])){ echo "Registeration for user: ".$_POST['username'];}   ?> 
        </h2>
        <p class="message_body">Place finger in fingerprint sensor</p>
    </div>
    </div>
   
<script> 
    window.iscounter = "<?php if(isset($counter)) echo $counter ?>";
    window.last_userid = "<?php echo $last_userid?>"; 
    window.username =  "<?php if(isset($_POST['username'])){ echo "".trim($_POST['username']);}   ?>";
    window.password =  "<?php if(isset($_POST['password1'])){ echo "".trim($_POST['password1']);}   ?>";
</script>
<script src="./register.js"></script>

</body>
</html>