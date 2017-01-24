<?php
/*session_start();
if(!isset($_SESSION['util'])) {
    header("Location: /index.php",TRUE,303);
    echo 'erreur de connexion';
}*/
?>
    
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Projet PHP - MongoDB</title>
</head>
<body>

<h1>Maintenance de la base de données</h1>
    <h1>Geo France</h1><br/>
    <h2>Vous êtes éditeur :</h2>
    
    <!--création du formulaire html-->
    <p>
    <form action="" method="">
    <fieldset>
        <legend>Mise à jour du code postal et de la population</legend>
        <p>Valeurs actuelles</p>
        <label>Code postal: <input type="text" name="cpnow"</label><br/>
        <label>Population: <input type="text" name="popnow"></label><br/>
        <p>Nouvelles valeurs</p>
        <label>Code postal: <input type="text" name="cpnew"></label><br/>
        <label>Population: <input type="text" name="popnew"></label><br/>
    </fieldset>
        <input type="submit" value="Valider">
        <input type="reset" value="Ré-initialiser">
    </form>
    </p>

<?php
 //   echo 'Bonjour&nbsp;'.$_SESSION['util'].'&nbsp;et Bienvenue.'
?>
    
</body>
</html>