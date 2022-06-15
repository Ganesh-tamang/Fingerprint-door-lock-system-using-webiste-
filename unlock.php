<?php
    session_start();
    if(!isset($_SESSION['account'])){
        header('location:./login.php');
        return;
    }
     require_once './model/database_function.php';
     $database = new QueryClass;
     
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unlock Table</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/unlock.css">
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
                        <li><a href="./unlock.php" class="actives">Unlock</a></li>
                        <li><a href="./logout.php">Logout</a></li>
                        
                    </ul>
                </nav>
            </div>
        </header>

    <section>
        <h3 style="text-align: end; margin-right: 15%; color:aqua">User Registration Code: 1234</h3>
  
        <h2 style="text-align: center; margin: 20px;">DOOR ACCESS TABLE</h2>

        <table>
        <tr>
            <th>UnlockTime</th>
            <th>User Name</th>
        </tr>
        <?php
            $dat = $database->get_unlock_data() ;
            $boxer ='';
            
            foreach($dat as $d){
                    $date = date("D, jS M Y, g:i A", strtotime($d['unlock_time']));
                    $boxer .= "<tr>
                    <td>{$date}</td>
                    <td>{$d['user_name']}</td>
                </tr>";
                }

            echo $boxer;
        ?>
         
         
        </table>
         
        
    </section>
   
</body>
</html>