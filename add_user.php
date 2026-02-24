<?php


if(isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['role']))
{

    if(!empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['role']))
    {

        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
            header("Location: index.php?error=invalidemail");
            exit();
        }

        if(!is_numeric($_POST['role'])){
            header("Location: index.php?error=invalidrole");
            exit();
        }


        try{
            $pdo = new PDO('mysql:host=localhost;dbname=curdphp', "root","");
        }catch(PDOException $e){
            echo "error: " . $e->getMessage();
        }


        $nom = htmlspecialchars($_POST['nom']);
        $email = htmlspecialchars($_POST['email']);
        $role = htmlspecialchars($_POST['role']);

        $selectuser = $pdo->query("SELECT * FROM users WHERE email = '$email'");
        if($selectuser->rowCount() > 0){
            header("Location: index.php?error=emailexists");
            exit();
        }

        $insertUser = $pdo->prepare("INSERT INTO users (nom, email, role) VALUES (:nom, :email, :role)");
        $insertUser->execute(['nom' => $nom, 'email' => $email, 'role' => $role]);

        header("Location: index.php?success=useradded");
    }
    else{
        header("Location: index.php?error=emptyfields");
    }

}
else{
    header("Location: index.php?error=invalidaccess");
}

