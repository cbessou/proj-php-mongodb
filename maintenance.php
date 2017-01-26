<?php
session_start();
//verif connexion et redirection
if(!isset($_SESSION['util'])) {
    header("Location: /index.php",TRUE,303);
    echo 'erreur de connexion';
}
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

<?php
//on verifie qu'on a bien recu la ville a modifier
if(isset($_GET['idv'])) {
    echo'<pre>';
    $idv = $_GET['idv'];
    print_r($idv);
    //on récupère les infos de la ville
    try { 
    // les paramètres de connexion
        $dsn='mongodb://localhost:27017';
    // création de l'instance de connexion
        $mgc = new MongoDB\Driver\Manager($dsn);

    // filtre sur l'id de la ville
    // préparation de la requête 
$filter = ['_id'=>$idv];
print_r($filter);
$options = ['projection' => ['nom' => 1, '_id_dept' => 1, 'pop' => 1, 'cp' => 1]];
$query = new MongoDB\Driver\Query($filter, $options);
print_r($query);
    // lancement de la requête
$curs = $mgc->executeQuery('geo_france.villes', $query);

print_r($curs);  
$res = $curs -> toArray(); 
print_r($res);
echo'</pre>';
    }
    catch(MongoDB\Driver\Exception $e) {
        // en cas d'erreur on montre le message reçu.
        die (sprintf("<h2>traitement de l'erreur survenue durant le traitement</h2>\n<pre>%s</pre>\n", $e->getMessage()));
    }

    

    
    echo'<!--création du formulaire html-->
    <form action="" method="">
    <fieldset>
        <legend>Mise à jour du code postal et de la population</legend>
        <p>Nouvelles valeurs</p>
        <label>Code postal: <input type="text" name="cpnew">Ancienne valeur</label><br/>
        <label>Population: <input type="text" name="popnew">Ancienne valeur</label><br/>';

    if($_SESSION['profil']=='admin') {
        echo'<label>Nom de la région: <input type="text" name="regnew">Ancienne valeur</label><br/>';
    }



    echo'</fieldset>
        <input type="submit" value="Valider">
        <input type="reset" value="Ré-initialiser">
    </form>';
} else {
    echo '<p>Aucune ville sélectionnée, <a href="index.php">choisissez une ville</a></p>';
}
;
 //   echo 'Bonjour&nbsp;'.$_SESSION['util'].'&nbsp;et Bienvenue.'
?>
    
</body>
</html>
