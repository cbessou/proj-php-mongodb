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
//requête d'écriture dans la base de données
try { 
   // les paramètres de connexion
    $dsn='mongodb://localhost:27017';
    // création de l'instance de connexion
   $mgc = new MongoDB\Driver\Manager($dsn); 

   if(isset($_POST['envoi'])) {
        $ncp = $_POST['cpnew'];
        if($ncp!=""){
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update(
                ['_id' => (int)$_GET['idv']],
                ['$set' => ['cp' => $ncp]]
            );
        $result = $mgc->executeBulkWrite('geo_france.villes', $bulk);
        }
        $npop = $_POST['popnew'];
        if($npop!=""){
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update(
                ['_id' => (int)$_GET['idv']],
                ['$set' => ['pop' => $npop]]
            );
        $result = $mgc->executeBulkWrite('geo_france.villes', $bulk);
        }
        if($_SESSION['profil']=='admin'){
            $nreg = $_POST['regnew'];
            $idr=(int)$_POST['idR'];
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
//on verifie qu'on a bien recu la ville a modifier
if(isset($_GET['idv'])) {
    echo'<pre>';
    $idv = (int)$_GET['idv'];
    // print_r($idv);
    //on récupère les infos de la ville

    // préparation de la requête 
        $filter = ['_id' => $idv];
        // print_r($filter);
        $options = ['projection' => ['nom' => 1, '_id_dept' => 1, 'pop' => 1, 'cp' => 1]];
        $query = new MongoDB\Driver\Query($filter, $options);
        //print_r($query);
    // lancement de la requête
        $curs = $mgc->executeQuery('geo_france.villes', $query);

        // print_r($curs);  
        $resV = $curs -> toArray();

        $filter = ['_id' => $resV[0]->_id_dept];
        $options = ['projection' => ['nom' => 1, '_id_region' => 1]];
        $query = new MongoDB\Driver\Query($filter, $options);
        $curs = $mgc->executeQuery('geo_france.departements', $query);
        $resD = $curs -> toArray();        

        $filter = ['_id' => $resD[0]->_id_region];
        $options = ['projection' => ['nom' => 1]];
        $query = new MongoDB\Driver\Query($filter, $options);
        $curs = $mgc->executeQuery('geo_france.regions', $query);
        $resR = $curs -> toArray();

        // print_r($res);
        echo'</pre>';


    

    

    
    echo'<!--création du formulaire html-->
    <form action="" method="POST">
    <fieldset>
        <legend>Modification possible sur les champs suivants</legend>
        <p>Nouvelles valeurs</p>
        <p>'.$resV[0] -> nom.'</p>
        <label>Code postal: <input type="text" name="cpnew">'.$resV[0] -> cp.'</label><br/>
        <label>Population: <input type="text" name="popnew">'.$resV[0] -> pop.'</label><br/>
        <p>'.$resD[0] -> nom.'</p>';

    if($_SESSION['profil']=='admin') {
        echo'<label>Nom de la région: <input type="text" name="regnew">'.$resR[0] -> nom.'</label><br/>';
        echo'<input type="hidden" name="idR" value='.$resR[0] -> _id.'>';
    }



    echo'</fieldset>
        <input type="submit" value="Valider" name="envoi">
        <input type="reset" value="Ré-initialiser">
    </form>';
} else {
    echo '<p>Aucune ville sélectionnée, <a href="index.php">choisissez une ville</a></p>';
}

}
    catch(MongoDB\Driver\Exception $e) {
        // en cas d'erreur on montre le message reçu.
        die (sprintf("<h2>traitement de l'erreur survenue durant le traitement</h2>\n<pre>%s</pre>\n", $e->getMessage()));
    }


 //   echo 'Bonjour&nbsp;'.$_SESSION['util'].'&nbsp;et Bienvenue.'
?>
    
</body>
</html>
