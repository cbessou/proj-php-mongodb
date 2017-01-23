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

$document1 = ['title' => 'admin'];
//$document2 = ['_id' => 'custom ID', 'title' => 'two'];
//$document3 = ['_id' => new MongoDB\BSON\ObjectID, 'title' => 'three'];

$_id1 = $bulk->insert($document1);
//$_id2 = $bulk->insert($document2);
//$_id3 = $bulk->insert($document3);

var_dump($_id1);

$manager = new MongoDB\Driver\Manager($dsn);
$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
$result = $manager->executeBulkWrite('db.users', $bulk, $writeConcern);

//print_r($result->toArray());exit;

// création de commande de parcours des de base de données
//$cmd = new MongoDB\Driver\Command(["listDatabases" => 1]);
// exécution de la commande dans la base 'admin'
//$cursor = $mgc->executeCommand("admin", $cmd);
// affichage des bases de données présentes
//$dbs = current($cursor->toArray());
//foreach($dbs->databases as $db) {
//  printf("-> %s\n", $db->name);
//}

} catch(MongoDB\Driver\Exception $e) {
  // en cas d'erreur on montre le message reçu.
  die (sprintf("<h2>traitement de l'erreur survenue durant le traitement</h2>\n<pre>%s</pre>\n", $e->getMessage()));
}
?>
</body>
</html>
