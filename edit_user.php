<?php

if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['role_id']))
{

    if(!empty($_POST['id']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['role_id']))
    {

        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
            header("Location: edit_user.php?id=" . htmlspecialchars(intval($_POST['id'])) . "&error=invalidemail");
            exit();
        }

        if(!is_numeric($_POST['role_id'])){
            header("Location: edit_user.php?id=" . htmlspecialchars(intval($_POST['id'])) . "&error=invalidrole");
            exit();
        }

        try{
            $pdo = new PDO('mysql:host=localhost;dbname=curdphp', "root","");
        }catch(PDOException $e){
            echo "error: " . $e->getMessage();
        }   

        $id = htmlspecialchars(intval($_POST['id']));
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $role_id = htmlspecialchars($_POST['role_id']);

        $updateUser = $pdo->prepare("UPDATE users SET nom = :name, email = :email, role = :role_id WHERE id = :id");
        $updateUser->execute(['name' => $name, 'email' => $email, 'role_id' => $role_id, 'id' => $id]); 
        header("Location: index.php?success=userupdated");
    }
    else{ 
             
        header("Location: edit_user.php?id=" . htmlspecialchars(intval($_POST['id'])) . "&error=emptyfields");
    }
}
  





if(!isset($GET['id'])){
    $id = htmlspecialchars(intval($_GET['id']));

    try{
        $pdo = new PDO('mysql:host=localhost;dbname=curdphp', "root","");
    }catch(PDOException $e){
        echo "error: " . $e->getMessage();
    }
    
    $selectUser = $pdo->prepare("SELECT users.*, roles.Nom as role_name FROM users INNER JOIN roles ON users.role = roles.id WHERE users.id = :id");
    $selectUser->execute(['id' => $id]);

    if($selectUser->rowCount() > 0){
        $user = $selectUser->fetch(PDO::FETCH_ASSOC);
        $selectRoles = $pdo->query("SELECT * FROM roles");
        $roles = $selectRoles->fetchAll(PDO::FETCH_ASSOC);
        
    }else{
        header("Location: index.php?error=usernotfound");
        exit();
    }

}
else{
    header("Location: index.php?error=invalidaccess");
    exit();
}


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edition utilisateur</title>
    <link rel="stylesheet" href="./assets/css/edit_user.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">
            <img src="./assets/images/logo.png" alt="Boomwerk Restaurant Logo">
        </div>
        <div>
            <a href="index.php">Tableau de bord</a>
        </div>
    </nav>
</header>
<h2 style="text-align: center;">Edition utilisateur</h2>
<?php

    if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
        header("Location: index.php?error=invalidaccess");
        exit();
    }   

    if(isset($_GET['error'])){
        if($_GET['error'] === 'emptyfields'){
            echo "<p class='error'>Veuillez remplir tous les champs.</p>";
        }elseif($_GET['error'] === 'invalidemail'){
            echo "<p class='error'>Adresse email invalide.</p>";
        }elseif($_GET['error'] === 'invalidrole'){
            echo "<p class='error'>Rôle invalide.</p>";
        }
    }

?>

<form action="edit_user.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['nom']); ?>">
    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>">
    <select name="role_id" id="role_id">
        <?php foreach($roles as $role): ?>
            <option value="<?php echo $role['id']; ?>" <?php echo ($user['id'] == $role['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($role['Nom']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Mettre à jour</button>
</form>


</body>
</html>




