<?php
session_start();
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

<?php
    echo 'Bonjour&nbsp;'.$_SESSION['util'].'&nbsp;et Bienvenue.'
?>
    
</body>
</html>