<?php

$t1=microtime();
require "inc_config.php";

spl_autoload_register("monAutoload");
//Recupération de l'URL
if (isset($_GET['url']))
	$data = explode("/", $_GET['url']);
else
	$data = null;
	
$controleur = (isset($data[0]) and $data[0]!="") ? $data[0] : "_default";
$action = (isset($data[1])  and $data[1]!="") ? $data[1] : "index";

//Les parametres
for ($i = 2; $i < count($data); $i+=2) {
	if (isset($data[$i]) and isset($data[$i + 1])) {
		$_GET[$data[$i]] = $data[$i + 1];
	}
}

autoconnect();
$ctr="Ctr_" . $controleur;
$oCtr=new $ctr($action);

$t2=microtime();
$tempsExec=$t2-$t1;
//historique des connexions
if ($controleur!="admin"){
	$objConnexion = new Connexion();
	$objConnexion->initData();
	if (isset($_SESSION["uti_id"]))
		$idUser=$_SESSION["uti_id"];
	else
		$idUser=null;
	$objConnexion->data["con_page"]=isset($_GET["url"]) ? $_GET["url"] : "index";
	$objConnexion->data["con_ip"]=isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
	$objConnexion->data["con_sessionID"]=isset($_COOKIE["PHPSESSID"]) ? $_COOKIE["PHPSESSID"] : null;
	$objConnexion->data["con_iduser"]=isset($_SESSION["uti_id"]) ? $_SESSION["uti_id"] : null;
	$objConnexion->data["con_tempsexecution"]=microtime(true)-$_SERVER["REQUEST_TIME_FLOAT"];
	$objConnexion->data["con_date"]=date("Y-m-d H:i:s");
	$objConnexion->data["con_provenance"]=isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null;
	$objConnexion->sauver();
}


 echo "<pre>";
 print_r($_SESSION);
 echo "</pre>";

// echo "<pre>";
/// print_r($_POST);
// echo "</pre>";

echo "<pre>";
print_r($_SERVER);
echo "</pre>";

//  echo "<pre>";
//  print_r($_COOKIE);
//  echo "</pre>";
?>