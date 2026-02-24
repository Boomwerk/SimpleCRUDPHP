<?php

if(isset($_GET['id'])){
    $id = htmlspecialchars(intval($_GET['id']));


    try {
        $pdo = new PDO("mysql:host=localhost;dbname=curdphp", "root", "");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);   

    if($stmt->execute()){
        header("Location: index.php?success=userdeleted");
        exit();
    }else{
        header("Location: index.php?error=deletefailed");  
        exit();
    }

    

}