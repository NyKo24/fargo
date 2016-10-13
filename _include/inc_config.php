<?php
session_start();
// Réglage de l'heure sur celle de Paris
date_default_timezone_set("Europe/Paris");
/**	Ce fichier est inclus sur toutes les pages du site et il doit être accessible via le include_path **/

/**
 * Titre du site
 * @var string
 */
define("TITRE_SITE","Fargo");

/**
 * URL du site (avec une "/" à la fin)
 * @var string
 */
define("BASE_URL","http://fargo.dev/");

/**
 * Chemin du repertoir contenant le projet (avec un "/" à la fin)
 * @var string
 */
define("BASE_REP","/var/www/fargo");

/**
 * Adresse du serveur de base de donnée
 * @var string
 */
define("DB_SERVER","db");

/**
 * Nom d'utilisateur de la base de donnée
 * @var string
 */
define("DB_USER","app");

/**
 * Mot de passe de la base de donnée
 * @var string
 */
define("DB_PWD","password");

/**
 * Nom de la base de donnée
 * @var unknown
 */
define("DB_BDD","fargo");

/**
 * Clé de cryptage sérvant au cryptage des mots de passe
 * @var string
 */
define("CLE_CRYPTAGE", "563dù^*b8bb49c");

/** Gestion des profils */
/**
 * Identifiant du profil ADMIN
 * @var int
 */
define("PROFIL_ADMIN", 5);

/**
 * Chargement du fichier Table.class.php
 */
require BASE_REP . "_framework/Table.class.php";

/**
 * Chargement du fichier Controleur.class.php
 */
require BASE_REP . "_framework/Controleur.class.php";

/** 
 * Chargement du fichier inc_fonction.php
 */
require BASE_REP . "_include/inc_fonction.php";

/**
 * Création d'une instance de connexion à la base de donnée
 */
Table::getCon(DB_SERVER,DB_USER,DB_PWD,DB_BDD);


/**
 * Défini le nombre de resultat par page dans l'application
 * @var INT
 */
define("NB_RESULT_PAGE", 50);

/**
 * tableau des mois de l'années
 * @var
 */
define("LISTE_MOIS", serialize(array(1=>"Janvier", 2=>"Février", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 7=>"Juillet", 8=>"Aout", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Decembre"))) ;


?>