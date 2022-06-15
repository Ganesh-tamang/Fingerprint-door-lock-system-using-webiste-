
<?php

    $host = 'localhost';
    $dbname = 'minorproject';
    $user = 'root';
    $pwd = 'user123';
    $pdo = new PDO("mysql:host={$host};dbname={$dbname}", $user, $pwd);        
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "insert into messages (information) value (:value)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':value' => $_POST['message']));
     

echo "message send";
