<?php 

try { 

// les paramètres de connexion
$dsn='mongodb://localhost:27017';
    
// création de l'instance de connexion
$mgc = new MongoDB\Driver\Manager($dsn);

    
//à remplacer par al variable recherchée
$ville='Toulouse';
$filter = ['nom' => $ville];
    
    
// création de requête
$query = new MongoDB\Driver\Query($filter);
    
    
// exécution de la requête par la connexion
$curs = $mgc->executeQuery(
   'geo_france.villes', // la collection visée
   $query               // la requête
);
    
    
// parcours du curseur résultant
foreach($curs as $doc) {
  echo $doc->nom, ' <br>pop: '.$doc->pop, '<br>cp: ', $doc->cp,'<br>lat: ', $doc->lat,'<br>lon: ',$doc->lon."\n";
}  

    
}

 catch(MongoDB\Driver\Exception $e) {
  // en cas d'erreur on montre le message reçu.
  die (sprintf("<h2>traitement de l'erreur survenue durant le traitement</h2>\n<pre>%s</pre>\n", $e->getMessage()));
}

?>