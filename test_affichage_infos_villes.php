<?php 
try { 
// les paramètres de connexion
$dsn='mongodb://localhost:27017';
// création de l'instance de connexion
$mgc = new MongoDB\Driver\Manager($dsn);
}
 catch(MongoDB\Driver\Exception $e) {
  // en cas d'erreur on montre le message reçu.
  die (sprintf("<h2>traitement de l'erreur survenue durant le traitement</h2>\n<pre>%s</pre>\n", $e->getMessage()));
}

?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Demo</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="Demo project">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <style type="text/css"></style>
    </head>

    <body>
        <form action="" method="get">
        <fieldset>
        <legend>Villes</legend>
        <label>Villes:
           <!-- <input type="text" name="nom" placeholder="Champ Obligatoire..." required>-->
        <input type="text" name="nom" placeholder="Champ Obligatoire...">
        </label>
        <br/>
        <label>Département:
            <input type="text" name="dpt">
        </label>
        <br/>
        <label>Région:
            <input type="text" name="reg">
        </label>
        <br/>
        </fieldset>
        <input type="submit" value="Valider">
        <input type="reset" value="Réinitialiser">
        </form>
        
        <?php 
       
        //Récupération des variables du formulaire. Declaration et vérification isset pour résoudre problème de variables non définis lors du premier chargement de la page
       /* $ville='non renseignée';
        $departements= 'non renseignée';
        $regions= 'non renseignée';*/
        
        if (isset($_GET['nom'])) { 
        $ville= $_GET['nom'];
        } 
       
        if (isset($_GET['dpt'])) { 
        $departements= $_GET['dpt'];
        }
        
        if (isset($_GET['reg'])) { 
         $regions= $_GET['reg'];
        }
        
        //Initialisation d'un tableau pour les résultats
        $resultat = [];
    
        ////////// VILLE //////////////
        $filterV = ['nom'=> new MongoDB\BSON\Regex('^'.$ville.'$','i')];
                    
        // création de requête
        $queryV = new MongoDB\Driver\Query($filterV);
    
        // exécution de la requête par la connexion
        $cursV = $mgc->executeQuery(
        'geo_france.villes',
        $queryV 
            );
            
        $resV = $cursV -> toArray(); 
        $compteV = count($resV);
        $filter =[];
        
        if ($compteV !==0) { 
            for ($i=0; $i < $compteV; $i++) {
                    echo ('<pre>');
                    print_r ($resV[$i]);
                    echo ('</pre>');
                
                    $id= $resV[$i] -> _id_dept;
                
                    if (isset($_GET['dpt'])) {
                        
                        $filterD = ['nom'=> new MongoDB\BSON\Regex('^'.$departements.'.*','i'), '_id'=>$id];
                    } else { 
                       
                         $filterD = ['_id'=>$id];
                    }
                    
                    $queryD = new MongoDB\Driver\Query($filterD);
                    $cursD = $mgc->executeQuery(
                    'geo_france.departements',
                    $queryD               
                    );
                   
                    $resD = $cursD -> toArray();
                
                echo ('<pre>');
                    print_r($filterD);
                    print_r ($resD);
                echo ('</pre>');
            } 
        }    
       
       
       /*if (isset($region)) {
           $filterR = ['nom'=> new MongoDB\BSON\Regex('^'.$regions.'$','i')];
           $queryR = new MongoDB\Driver\Query($filterR);
           
           $cursR_one = $mgc->executeQuery(
           'geo_france.regions',
           $queryR    
           );
       }
           
        else {
        $filterR = [];
        $queryR = new MongoDB\Driver\Query($filterR);
        $cursR_all = $mgc->executeQuery(
        'geo_france.regions',
        $queryR   
        );
        }
             */
        
        
        
        ////////////////////// REGIONS //////////////////
        //Problème de caractères -> ex: haute-garonne non trouvée
        
        // création de requête
          
        
        // exécution de la requête par la connexion
       /* */
        
                  
        /////////////////// Affichage ////////////////////
       /* foreach($cursV as $docV) {
        echo $docV->nom, ' <br>pop: '.$docV->pop, '<br>cp: ', $docV->cp,'<br>lat: ', $docV->lat,'<br>lon: ',$docV->lon."\n";
        } 
    */
        /*Afficher régions
        foreach($cursR as $docR) {
        echo '<br>Région: '.$docR->nom;
         } */
        
    
        // pour afficher departements
      /*   foreach($cursD as $docD) {
        echo '<br>Departement: '.$docD->nom;
         } 
        */

        
        ?>

    </body>

    </html>