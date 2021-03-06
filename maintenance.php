<?php
session_start();
// vérification connexion et redirection
if(!isset($_SESSION['util'])) {
    header("Location: index.php",TRUE,302);
    exit;
}
?>
<!-- -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet PHP - MongoDB</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div id="page_index">
    <header>
        <h1>Gestion de la base de données Geo France</h1>
        <form name='login' action="index.php" method="post">
            <?php
            if (isset($_SESSION['util'])){
                echo 'Connecté en tant que '.$_SESSION['util'].' ('.$_SESSION['profil'].') ';
                echo '<input type="submit" name="deco" value="Déconnexion">';
            }
            ?>
        </form>
    </header>
        <h2>Maintenance de la base de données :</h2>
<?php
// requêtes d'écriture dans la base de données
try { 
    // paramètres de connexion
    $dsn='mongodb://localhost:27017';
    // création de l'instance de connexion
    $mgc = new MongoDB\Driver\Manager($dsn); 
    // condition de validation des modifications
    if(isset($_POST['envoi'])) {
        // changement du code postal
        $ncp = $_POST['cpnew'];
        // condition nouveau code postal non vide
        if($ncp!=""){
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update(
                ['_id' => (int)$_GET['idv']],
                ['$set' => ['cp' => $ncp]]
            );
        $result = $mgc->executeBulkWrite('geo_france.villes', $bulk);
        }
        // changement de la population
        $npop = $_POST['popnew'];
        // condition nouvelle population non vide
        if($npop!=""){
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update(
                ['_id' => (int)$_GET['idv']],
                ['$set' => ['pop' => $npop]]
            );
        $result = $mgc->executeBulkWrite('geo_france.villes', $bulk);
        }
        // changement du nom de la région
        if($_SESSION['profil']=='admin'){
            $nreg = $_POST['regnew'];
            $idr=(int)$_POST['idR'];
            // condition nouveau nom de région non vide
            if($nreg!=""){
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->update(
                    ['_id' => $idr],
                    ['$set' => ['nom' => $nreg]]
                );
            $result = $mgc->executeBulkWrite('geo_france.regions', $bulk);
            }
        }
    }
// vérification qu'on a bien reçu la ville à modifier
if(isset($_GET['idv'])) {
    $idv = (int)$_GET['idv'];
    // récupération des infos de la ville
    // préparation de la requête sur villes
    $filter = ['_id' => $idv];
    $options = ['projection' => ['nom' => 1, '_id_dept' => 1, 'pop' => 1, 'cp' => 1]];
    $query = new MongoDB\Driver\Query($filter, $options);
    $curs = $mgc->executeQuery('geo_france.villes', $query);
    $resV = $curs -> toArray();
    // préparation de la requête sur départements
    $filter = ['_id' => $resV[0]->_id_dept];
    $options = ['projection' => ['nom' => 1, '_id_region' => 1]];
    $query = new MongoDB\Driver\Query($filter, $options);
    $curs = $mgc->executeQuery('geo_france.departements', $query);
    $resD = $curs -> toArray();        
    // préparation de la requête sur régions
    $filter = ['_id' => $resD[0]->_id_region];
    $options = ['projection' => ['nom' => 1]];
    $query = new MongoDB\Driver\Query($filter, $options);
    $curs = $mgc->executeQuery('geo_france.regions', $query);
    $resR = $curs -> toArray();
    echo '<h3>'.$resV[0] -> nom.'</h3>';
    // création du formulaire html
    echo'<form action="#" method="POST">
    <fieldset>
        <legend>Modification possible sur les champs suivants</legend>
        <label>Code postal: <input type="text" name="cpnew">'.$resV[0] -> cp.'</label><br/>
        <label>Population: <input type="text" name="popnew">'.$resV[0] -> pop.'</label><br/>
        <p>'.$resD[0] -> nom.'</p>';
    // création du champ nom de région à modifier si profil admin
    if($_SESSION['profil']=='admin') {
        echo'<label>Nom de la région: <input type="text" name="regnew">'.$resR[0] -> nom.'</label><br/>';
        echo'<input type="hidden" name="idR" value='.$resR[0] -> _id.'>';
    }
    echo'</fieldset>
        <input type="submit" value="Valider" name="envoi">
        <input type="reset" value="Réinitialiser">
    </form>';
} else {
    echo '<p>Aucune ville sélectionnée, <a href="index.php">choisissez une ville</a></p>';
}
}
    catch(MongoDB\Driver\Exception $e) {
        // en cas d'erreur affichage du message reçu
        die (sprintf("<h2>traitement de l'erreur survenue durant le traitement</h2>\n<pre>%s</pre>\n", $e->getMessage()));
    }
?>
</div>
</body>
</html>