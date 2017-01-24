<?php
required_once(users.php);

$user1=$document1.nom;
$mdp1=$document.mdp;
$user2=$document2.nom;
$mdp2=$document2.mdp;

if(isset($_POST['util']==$user1 && $_POST['mdp']==$mdp1)) {
	header("location:/maintenance.php",TRUE);
}
elseif(isset($_POST['util']==$user2 && $_POST['mdp']==$mdp2)) {
	header("location:/maintenance.php",TRUE);
}
else {
	header("location:/index.php",TRUE);
}
?>