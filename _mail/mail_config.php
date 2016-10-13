<?
/**
 * Fichier de configuration pour l'envoie de mail
 * @author Nicolas BORDES <nicolasbordes@me.com>
 * 
 */

/**
 * Adresse du serveur mail (IP / URL
 * @var string
 */
define("MAIL_SERVER", "maildev");

/**
 * Active ou non l'authentification par nom d'utilisateur / mot de passe
 * @var Boolean
 */
define("MAIL_AUTH",false);

/**
 * Nom d'utilisateur (null si non utilisé)
 * @var string
 */
define("MAIL_USER", "archimede@adviseme.fr");

/**
 * Mot de passe (null si non utilisé)
 * @var string
 */
define("MAIL_PASSWORD", "Archimede2016");

/**
 * Numéro de port du serveur
 * @var int
 */
define("MAIL_PORT", 25);

/**
 * Type de connexion (SSL ou TLS)
 * @var string
 */
define("MAIL_CRYPTAGE", "TLS"); // SSL or TLS

/**
 * Adresse mail de l'expéditeur
 * @var string
 */
define("MAIL_ADRESSE", "archimede@adviseme.fr");

/**
 * Nom de l'expéditeurs
 * @var unknown
 */
define("MAIL_NOM_PRENOM","Archimède");

/**
 * Adresse email de réponse ajouté dans le mail
 * @var unknown
 */
define("MAIL_ADRESSE_REPLY","timea@archimede.asso.fr");
/**
 * Active ou non l'envoie de mail 
 * @var Boolean
 */
define("ENVOI_MAIL_OK",true);
?>