<?php

    try{

        $pdo = new PDO('mysql:host=localhost;dbname=curdphp', "root","");
    }catch(PDOException $e){
        echo "error: " . $e->getMessage();
    }

    $selectUser = $pdo->query("SELECT * FROM users INNER JOIN roles ON users.role = roles.id");
    $users = $selectUser->fetchAll(PDO::FETCH_NAMED);
    $selectRoles = $pdo->query("SELECT * FROM roles");
    $roles = $selectRoles->fetchAll(PDO::FETCH_ASSOC);

    
    $pdo=null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boomwerk Restaurant</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    
</head>
<body>

<header>
    <nav>
        <div class="logo">
            <img src="./assets/images/logo.png" alt="Boomwerk Restaurant Logo">
        </div>
        <div>
        </div>
    </nav>
</header>

<main>
   <div class="sousmenu">
  
   </div>

   <div class="dashboard">

   <?php
        if(isset($_GET['success']) && $_GET['success'] === 'useradded'){
            echo "<p class='success'>Utilisateur ajouté avec succès!</p>";
        }elseif(isset($_GET['success']) && $_GET['success'] === 'userupdated'){
             echo "<p class='success'>Utilisateur mis à jour avec succès!</p>";
        }elseif(isset($_GET['success']) && $_GET['success'] === 'userdeleted'){
             echo "<p class='success'>Utilisateur supprimé avec succès!</p>";
        }
    

        if(isset($_GET['error'])){
            if($_GET['error'] === 'emptyfields'){
                echo "<p class='error'>Veuillez remplir tous les champs.</p>";
            }elseif($_GET['error'] === 'invalidaccess'){
                echo "<p class='error'>Accès invalide. Veuillez soumettre le formulaire correctement.</p>";
            }elseif($_GET['error'] === 'invalidemail'){
                echo "<p class='error'>Email invalide. Veuillez entrer une adresse email valide.</p>";  
            }elseif($_GET['error'] === 'invalidrole'){
                echo "<p class='error'>Rôle invalide. Veuillez sélectionner un rôle valide.</p>";
            }elseif($_GET['error'] === 'emailexists'){
                echo "<p class='error'>Cet email est déjà utilisé.</p>";
            }elseif($_GET['error'] === 'updatefailed'){
                echo "<p class='error'>Échec de la mise à jour de l'utilisateur.</p>";
            }elseif($_GET['error'] === 'deletefailed'){
                echo "<p class='error'>Échec de la suppression de l'utilisateur.</p>";
            }
    

        }

        if(isset($_GET['delete'])){
            ?>

    <script>
        if(confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur?")){
            window.location.href = "delete_user.php?id=<?= $_GET['delete'] ?>";
        }else{
            window.location.href = "index.php";
        }
    </script>
<?php
        }
    ?>

    <h2>Utilsateurs</h2>

    <form action="add_user.php" method="post">
        <input type="text" name="nom" placeholder="Nom de l'utilisateur" required>
        <input type="text" name="email" placeholder="Email de l'utilisateur" required>
        <select name="role" required>
            <option value="">Sélectionnez un rôle</option>
            <?php
                foreach ($roles as $role) {
                    echo "<option value='" . $role['id'] . "'>" . htmlspecialchars($role['Nom']) . "</option>";
                }   
            ?>
        </select>
        <button type="submit" [disabled]="!form.form.valid">
            Ajouter
        </button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Actions</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['Nom']) ?></td>
                    <td><a href="edit_user.php?id=<?= $user['id'][0] ?>">Modifier</a> <a href="index.php?delete=<?= $user['id'][0] ?>">Supprimer</a></td>
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
   </div>
   



</main>
    
</body>
</html>