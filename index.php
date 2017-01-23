<?php
session_start();
if(isset($_POST['deco'])){
    session_destroy();
    session_start();
}
if(isset($_POST['util'])&&isset($_POST['mdp'])){
    //interroger la base de données
    //si corespondance la session recoit l'identifiant et le type
    $_SESSION['util']=$_POST['util'];
    
    //sinon erreur identifiant ou mdp
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Projet PHP - MongoDB</title>
</head>
<body>
    <header>
        <form name='login' action="" method="post">
            <?php
            if (isset($_SESSION['util'])){
                echo '<input type="submit" name="deco" value="Déconnexion">';
                echo '<a href="maintenance.php">Page de maintenance</a>';
            }else{
                echo '<input type="text" name="util" placeholder="Utilisateur">
            <input type="password" name="mdp" placeholder="Mot de passe">
            <input type="submit" value="Connexion">';
            }
            ?>
        </form>
    </header>
    <h1>Bienvenue sur notre Site.</h1>
    <h1>Geo France.</h1><br/>
    <h2>Géolocalisation par Recherche</h2>
    <h2>en France Métropolitaine et Corse.</h2><br/>
    <p>
        
    // création du formulaire html
    <form action="" method="post">
    <fieldset>
        <legend>Villes</legend>
        <label>Villes: <input type="text" name="villes">&nbsp;Champs Obligatoire.</label>
        <label>Département: <input type="text" name="departement">&nbsp;Champs Facultatif.</label>
        <label>Région: <input type="text" name="region">&nbsp;Champs Facultatif.</label>
        </fieldset>
        <input type="submit" value="Valider">
        <input type="reset" value="Réinitialiser">
    </form>
    </p>
    
</body>
</html>