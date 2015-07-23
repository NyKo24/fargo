<?php
/*
* Configuration de l'installation de FARGO 
* V.1.6.2
*
* ATTENTION : Les chemin de fichier doivent être avec "/" (slash) et non pas des "\" (anti-slash)
*
*
*/

// 1. Apache 

// Chemin absolu du fichier httpd.con
$cheminHTTP="C:/wamp/bin/apache/apache2.2.22/conf/httpd.conf";

//Nom du vhost (serverName)
$serveurName="lesuperboncoup";

/*
* IP du VHOST : 
* 127.0.0.1 pour un usage en local
* IP Publique : accèsible depuis l'exterieur sur le reseau
*/
$serveurIP="127.0.0.1";

//Repertoir ou ce situe le projet (DocumentRoot) 
// ATTENTION : ajouter un "/" à la fin
$chemin="D:/workspacePHP/lesuperboncoup/";
$documentRoot=$chemin."www";
$includePath=$chemin."_include";

// 2. MySQL

//addresse du serveur MySQL
$serveurBDD="localhost";

//login MySQL
$userBDD="root";

//Mot de passe MySQL
$passBDD="";

//Nom de la base MySQL
$nameBDD="lesuperboncoup";

// 3. Configuration du site

//Titre du site (const TITRE_SITE)
$siteName="Le Super Bon Coup alpha";

//URL du site
// ATTENTION ajouter un "/" a la fin
// * Correspond au serverName *
$url="http://lesuperboncoup/";

/*
* Clé de cryptage des mots de passe
* Si laisser vide alors génération d'une clé avec unicid()
*/
$cleCryptage="";

//Activer les sessions ?
// defaut NON
$session=true;

//profil admin
// ID correspondant au profil admin
$admin=3;

/*
* La configuration est fini
* exécuter le fichier en ligne de commande ou bien via le web
* ENJOY ! 
*/
echo "Création du VHOST: \n\n";
echo "Création d'un backup de votre HTTPD.CONF\n";
$f=fopen(str_ireplace("httpd.conf", "", $cheminHTTP)."httpd.conf_backup".strtotime("now"), "w");
fwrite($f, file_get_contents($cheminHTTP));
fclose($f);
echo "fait \n";
echo "Ecriture dans le HTTPD.CONF\n";
$f=fopen($cheminHTTP, "a+");

$chaine=file_get_contents("vhost_template.txt");
$chaine=str_ireplace("[serveurIP]", $serveurIP, $chaine);
$chaine=str_ireplace("[serveurName]", $serveurName, $chaine);
$chaine=str_ireplace("[documentRoot]", $documentRoot, $chaine);
$chaine=str_ireplace("[includePath]", $includePath, $chaine);

fwrite($f, "\n\n\n" . $chaine);
fclose($f);
echo "fait \n";

echo "Configuration du site : \n";
$chaine=file_get_contents("inc_config.php");
$chaine=str_ireplace("[titreSite]", $siteName, $chaine);
$chaine=str_ireplace("[url]", $url, $chaine);
$chaine=str_ireplace("[rep]", $chemin, $chaine);
$chaine=str_ireplace("[serveurBDD]", $serveurBDD, $chaine);
$chaine=str_ireplace("[loginBDD]", $userBDD, $chaine);
$chaine=str_ireplace("[passBDD]", $passBDD, $chaine);
$chaine=str_ireplace("[baseBDD]", $nameBDD, $chaine);
if ($session)
	$chaine=str_ireplace("[session]", "", $chaine);
else
	$chaine=str_ireplace("[session]", "//", $chaine); 

if ($cleCryptage=="") {
	$cle=uniqid();
	$chaine=str_ireplace("[clecryptage]", $cle, $chaine); 
} else{
	$chaine=str_ireplace("[clecryptage]", $cleCryptage, $chaine); 
}
$chaine=str_ireplace("[profiladmin]", $admin, $chaine);
file_put_contents("../_include/inc_config.php", $chaine);
echo "fait \n";
echo "La configuration est maintenant terminé, vous pouvez redémarer apache";