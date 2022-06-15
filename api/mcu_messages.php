 <?php
   
    require_once '../model/database_function.php';
    $database = new QueryClass;
    
    $last_message=$database->get_last_message();
    if(isset($_POST['get_message'])){
         echo $last_message['information'];      
    }
   
        
   
       
       
                
    
   