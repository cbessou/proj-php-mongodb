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
            <fieldset>Nom
                <input type="text" name="nom" />
            </fieldset>
            <input type="submit" value="Envoyer">
        </form>
<?php 
        $ville=$_GET['nom'];
        
        //à remplacer par al variable recherchée

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
        
        
        
        
        
        ?>

    </body>

    </html>