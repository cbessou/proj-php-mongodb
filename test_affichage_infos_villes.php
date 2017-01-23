<?php 

try { 

// les paramètres de connexion
$dsn='mongodb://localhost:27017';
    
// création de l'instance de connexion
$mgc = new MongoDB\Driver\Manager($dsn);

/*
// création de commande de parcours des bases de données
$cmd = new MongoDB\Driver\Command(["listDatabases" => 1]);
    
exécution de la commande dans la base 'admin'
$cursor = $mgc->executeCommand("admin", $cmd);
    
// affichage des bases de données présentes
$dbs = current($cursor->toArray());
foreach($dbs->databases as $db) {
  printf("-> %s\n", $db->name);
}*/
    

    
// Recupération dans les collections Ville / departements / Regions 
    
    
$query = new MongoDB\Driver\Query(
   ['pop'=>[1]],
   ['_id'=> 0]                  // pas d'identifiant affiché
);
    
// exécution de la requête par la connexion
$curs = $mgc->executeQuery(
   'geo_france.villes', // la collection visée
   $query               // la requête
    
);
    
    
// parcours du curseur résultant
foreach($curs as $doc) {
  echo $doc->nom, ' ', $doc->pop."\n";
}
    
     
}

 catch(MongoDB\Driver\Exception $e) {
  // en cas d'erreur on montre le message reçu.
  die (sprintf("<h2>traitement de l'erreur survenue durant le traitement</h2>\n<pre>%s</pre>\n", $e->getMessage()));
}

?>