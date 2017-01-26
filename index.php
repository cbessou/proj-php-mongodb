<?php
session_start();
if(isset($_POST['deco'])){
    session_destroy();
    session_start();
}

    //interroger la base de données
    $dsn='mongodb://localhost:27017';
    $dbname = 'geo_france';
    $collname = 'users';

try {
  $mgc = new MongoDB\Driver\Manager($dsn);
  if(isset($_POST['util'])&&isset($_POST['mdp'])){
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
  }
} catch(MongoDB\Driver\Exception $e) {
  // en cas d'erreur on montre le message reçu.
   printf("<h2>erreur de connexion</h2>\n<pre>%s</pre>\n", $e->getMessage());
}

?>
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
        <h1>Bienvenue sur notre Site<br/>Geo France</h1>
        <form name='login' action="" method="post">
            <?php
            if (isset($_SESSION['util'])){
                echo '<input type="submit" name="deco" value="Déconnexion">';
                
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
    <form action="" method="get">
    <fieldset>
        <legend>Villes</legend>
        <label>Villes: <input type="text" name="nom" placeholder="Champ Obligatoire..." required></label><br/>
        <label>Département: <input type="text" name="dpt"></label><br/>
        <label>Région: <input type="text" name="reg"></label><br/>
        </fieldset>
        <input name ="rech" type="submit" value="Valider">
        <input type="reset" value="Réinitialiser">
    </form>
    <?php 
       
       
        if(isset($_GET['rech'])){
            //récupérer les valeurs
            if(isset($_GET['nom'])){ 
                $ville= $_GET['nom'];
            }
            if(isset($_GET['dpt'])){ 
                $dept= $_GET['dpt'];
            }
            if(isset($_GET['reg'])){ 
                $region= $_GET['reg'];
            }
       
            //Initialisation d'un tableau pour les résultats
            $resultat = [];
    
            //Recherche de la ville par l'utilisation d'une regex qui permet de la rendre insensible à la case
            $filterV = ['nom'=> new MongoDB\BSON\Regex('^'.$ville.'$','i')];
            $options = ['projection' => ['nom' => 1, '_id_dept' => 1]];
            // création de requête
            $queryV = new MongoDB\Driver\Query($filterV, $options);
    
            // exécution de la requête par la connexion
            $cursV = $mgc->executeQuery('geo_france.villes', $queryV);
            $resV = $cursV -> toArray(); 

            $compteV = count($resV);
        
            if ($compteV !==0) {
                for ($i=0; $i < $compteV; $i++) {
                    $id = $resV[$i] -> _id_dept;
                    $filterD = ['nom'=> new MongoDB\BSON\Regex('^'.$dept.'.*','i'), '_id'=>$id];
                    $options = ['projection' => ['nom' => 1, '_id_region' => 1]];
                    $queryD = new MongoDB\Driver\Query($filterD, $options);
                    $cursD = $mgc->executeQuery('geo_france.departements', $queryD);
                
                    $resD = $cursD -> toArray();
                    $compteD = count($resD);
                   
                    if($compteD==1){
                        $id = $resD[0] -> _id_region;
                        $options = ['projection' => ['nom' => 1]];
                        $filterR = ['nom'=> new MongoDB\BSON\Regex('^'.$region.'.*','i'), '_id'=>$id];
                        $queryR = new MongoDB\Driver\Query($filterR, $options);
                        $cursR = $mgc->executeQuery('geo_france.regions', $queryR);
                        $resR = $cursR -> toArray();
                        if(count($resR)==1){
                            $tmp=[];
                            foreach($resV[$i] as $key => $value){
                                $tmp[$key]= $value;
                            }
            
                            foreach($resD[0] as $key => $value){
                                if($key=='nom'){
                                    $tmp['nom-dpt']=$value;
                                }else if($key!='_id'){
                                    $tmp[$key]=$value;
                                }
                            }
                            foreach($resR[0] as $key => $value){
                                if($key=='nom'){
                                    $tmp['nom-reg']=$value;
                                }else if($key!='_id'){
                                    $tmp[$key]=$value;
                                }
                            }
                            $resultat[]=$tmp;
                        }
                    }
                }               
            } 
           
            $t_result= count ($resultat);
            if ($t_result==0) {
                echo ('<p>Aucune ville trouvée.</p>');
            } elseif ($t_result==1) { 
                echo '<div class="resultats">';
                
                $oneresultat = $resultat[0];
                echo ('<h4>'.$oneresultat['nom'].'</h4>');
                echo ('Departement: '.$oneresultat['nom-dpt'].'<br/>');
                echo ('Region: '.$oneresultat['nom-reg'].'<br/>');

                $filterV = ['_id'=> $oneresultat['_id'] ];
                // création de requête
                $queryV = new MongoDB\Driver\Query($filterV);

                $cursV = $mgc->executeQuery('geo_france.villes', $queryV);
                $resV = $cursV -> toArray(); 
                
                echo '<p>';
                    if (isset($resV[0]->cp)) {
                        echo 'Code Postal: ';
                        echo $resV[0]->cp;
                        echo '<br/>'; 
                    }

                    if (isset($resV[0]->pop)) {
                        echo 'Population: ';
                        echo $resV[0]->pop; 
                        }
                echo '</p>';

                echo '<p>Coordonées: <br/>';

                    if (isset($resV[0]->lat)) {
                        echo 'Latitude: ';
                        echo $resV[0]->lat; 
                        echo '<br/>';
                    }

                    if (isset($resV[0]->lon)) {
                        echo 'Longitude: ';
                        echo $resV[0]->lon; 
                        echo '</p>';
                    }
                    echo '</p>';


                if (isset($_SESSION['util'])){
                    echo ('<a href="maintenance.php?idv='.$oneresultat['_id'].'">Modifier la ville</a>');
                }

                echo '</div>';

            }  elseif ($t_result>1) {

                echo '<div class="resultats">';
                echo ('<p>Plusieurs villes correspondent à votre recherche.<br/> Veuillez préciser votre demande:</p>');
              
                foreach($resultat as $iresult) {
                     echo ('<p><a href="index.php?nom='.$iresult['nom'].'&dpt='.$iresult['nom-dpt'].'&reg='.$iresult['nom-reg'].'&rech=Valider" >'.$iresult['nom'].' ('.$iresult['nom-dpt'].')</a></p>');
                 }   
                 echo '</div>'; 
            }
        }     
        ?>
    </div> 
</body>
</html>