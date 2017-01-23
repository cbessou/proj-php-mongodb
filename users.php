<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Creation de la collection 'users'</title>
  <style type="text/css">
    samp {background-color:#000; color:#FF0}
    em {color:#006}
  </style>
</head>
 
<body>
  <h1>Création et modification de la collection 'users'</h1>
<?php

//$dsn='mongodb://localhost:27017';
//$dbname = 'geo_france';
//$collname = 'users';

try {
    // les paramètres de connexion
$dsn='mongodb://localhost:27017';
// création de l'instance de connexion
//$mgc = new MongoDB\Driver\Manager($dsn);

$bulk = new MongoDB\Driver\BulkWrite;
//création des documents
$document1 = ['nom' => 'admin', 'mdp' => 'admin', 'profil' => 'admin'];
$document2 = ['nom' => 'edit', 'mdp' => 'edit', 'profil' => 'edit'];

$_id1 = $bulk->insert($document1);
$_id2 = $bulk->insert($document2);

$manager = new MongoDB\Driver\Manager($dsn);
//$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
$result = $manager->executeBulkWrite('geo_france.users', $bulk);
    
    echo "La table users a été remplie<br>";
    echo '<pre>';
    print_r($document1);
    //echo '<br>';
    print_r($document2);
    echo '</pre>';

} catch(MongoDB\Driver\Exception $e) {
  // en cas d'erreur on montre le message reçu.
  die (sprintf("<h2>traitement de l'erreur survenue durant le traitement</h2>\n<pre>%s</pre>\n", $e->getMessage()));
}
?>
</body>
</html>
