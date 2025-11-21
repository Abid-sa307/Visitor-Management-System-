<?php
$host = '127.0.0.1';
$user = 'root';
$pass = ''; // Try with empty password first
$db   = 'visitor';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "Connected to database successfully!";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    
    // Try with root password if empty password fails
    if (empty($pass)) {
        echo "\nTrying with password 'root'...";
        $pass = 'root';
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            echo "\nConnected to database with password 'root'!";
        } catch(PDOException $e2) {
            echo "\nConnection still failed with password 'root': " . $e2->getMessage();
        }
    }
}
