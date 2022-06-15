<?php

$host = 'localhost';
$dbname = 'minorproject';
$user = 'root';
$pwd = 'user123';
 
$pdo = new PDO("mysql:host={$host};dbname={$dbname}", $user, $pwd);        
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "insert into users (user_name, password) value (:value,:value2)";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':value' => $_POST['username'],':value2' => $_POST['password']));

    echo "user inserted";
    