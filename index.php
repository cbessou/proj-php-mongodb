<?php
session_start();
if(isset($_POST['deco'])){
    session_destroy();
    session_start();
}
if(isset($_POST['util'])&&isset($_POST['mdp'])){
    //interroger la base de données
    $dsn='mongodb://localhost:27017';
    $dbname = 'geo_france';
    $collname = 'users';

try {
  $mgc = new MongoDB\Driver\Manager($dsn);
 
  $filter = ['nom' => $_POST['util'],'mdp' => $_POST['mdp']];
  $query = new MongoDB\Driver\Query($filter);
  $find = $mgc->executeQuery($dbname.'.'.$collname, $query);
  $result = $find->toArray();
  //print_r($result);
  //print_r(count($result));
  if(count($result)==1) {
    $_SESSION['util']=$result[0]->nom;
    $_SESSION['profil']=$result[0]->profil;
  }
} catch(MongoDB\Driver\Exception $e) {
  // en cas d'erreur on montre le message reçu.
   printf("<h2>erreur de connexion</h2>\n<pre>%s</pre>\n", $e->getMessage());
}
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Projet PHP - MongoDB</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body> 
    <div id="page_index">
    <header>
        <h1>Bienvenue sur notre Site<br/>Geo France</h1>
        <form name='login' action="" method="post">
            <?php
            if (isset($_SESSION['util'])){
                echo '<input type="submit" name="deco" value="Déconnexion">';
                echo '<a href="maintenance.php">Page de maintenance</a>';
            }else{
                echo '<input type="text" name="util" placeholder="Utilisateur" required>
            <input type="password" name="mdp" placeholder="Mot de passe" required>
            <input type="submit" value="Connexion">';
            }
            ?>
        </form>
    </header>
       <h2>Géolocalisation par recherche <br/> en France Métropolitaine et Corse.</h2>
    
    
    <!--création du formulaire html-->
    <p>
    <form action="" method="get">
    <fieldset>
        <legend>Villes</legend>
        <label>Villes: <input type="text" name="nom" placeholder="Champ Obligatoire..." required></label><br/>
        <label>Département: <input type="text" name="dpt"></label><br/>
        <label>Région: <input type="text" name="reg"></label><br/>
        </fieldset>
        <input type="submit" value="Valider">
        <input type="reset" value="Réinitialiser">
    </form>
    </p>
    </div> 
</body>
</html>