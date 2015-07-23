<?php
[session]session_start();
/**	Ce fichier est inclus sur toutes les pages du site et il doit être accessible via le include_path **/
define("TITRE_SITE","[titreSite]");
define("BASE_URL","[url]");
define("BASE_REP","[rep]");
define("DB_SERVER","[serveurBDD]");
define("DB_USER","[loginBDD]");
define("DB_PWD","[passBDD]");
define("DB_BDD","[baseBDD]");

//cryptage A CHNAGER OBLIGATOIREMENT ! 
define("CLE_CRYPTAGE", "[clecryptage]");

//profil
define("PROFIL_ADMIN", [profiladmin]);
require BASE_REP . "_framework/Table.class.php";
require BASE_REP . "_framework/Controleur.class.php";
require BASE_REP . "_include/inc_fonction.php";
Table::getCon(DB_SERVER,DB_USER,DB_PWD,DB_BDD);


?>