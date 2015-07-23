<?//fonction d'envoie de mail
require_once(BASE_REP."_mail/mail_config.php");
/*
 * Envoie un email à chaque personne de $arrayTo avec pour sujet $subject et comme contenu $contenu
 * 
 * @paran $arrayTo array : Tableau indicé contenant les adresse des email des destinataires 
 * 	ATTENTION : chaque envoie nécéssite une connexion au serveur SMTP 
 * @param $subject string : Sujet de l'email
 * @param $contenu string : chaine de caractère en HTML reprensant le corps du mail
 * @param [$debug] booleen defaut=false : active le mode debug
 */
function envoyerUnMail($arrayTo,$subject, $contenu,$debug=false) {
	require_once("phpmailer/class.phpmailer.php");
	require_once("phpmailer/class.smtp.php");

	$mail = new PHPMailer();

	$body = $contenu;
	$body = eregi_replace("[\]", '', $body);

	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->CharSet="UTF-8";
	$mail->Host = MAIL_SERVER;
	$mail->SMTPDebug = 2;
	$mail->SMTPAuth = MAIL_AUTH;
	$mail->SMTPSecure=MAIL_CRYPTAGE;
	$mail->Port = MAIL_PORT;
	$mail->Username = MAIL_USER;
	$mail->Password = MAIL_PASSWORD;
	$mail->SetFrom(MAIL_ADRESSE, TITRE_SITE);
	$mail->AddReplyTo(MAIL_ADRESSE, TITRE_SITE);
	$mail->Subject = $subject;

	$mail->MsgHTML($body);

	foreach($arrayTo as $adresse) {
		$mail->AddAddress($adresse, $adresse);
	}

	/**
	 * ENVOI et DEBUG
	 */
	if (ENVOI_MAIL_OK) {
		if (!$mail->Send()) {
			print_r("Mailer Error: " . $mail->ErrorInfo);
		} elseif ($debug) {
			print_r("Message envoyé à:" . print_r($arrayTo));
			print_r($contenu);
			print_r("---------------\n");
		}
	} elseif ($debug) {
		print_r("Simulation de Message envoyé à :" . print_r($arrayTo));
		print_r($contenu);
		print_r("---------------\n");
	}
}

/*
 * Envoie un email unique en metant toute les adresse de $arrayTo en Cci, avec pour sujet $subject et comme contenu $contenu
 *
 * @paran $arrayTo array : Tableau indicé contenant les adresse des email des destinataires
 * @param $subject string : Sujet de l'email
 * @param $contenu string : chaine de caractère en HTML reprensant le corps du mail
 * @param [$debug] booleen defaut=false : active le mode debug
 */
function envoyerMailGroupe($arrayTo,$subject, $contenu,$debug=false) {
	require_once("phpmailer/class.phpmailer.php");

	$mail = new PHPMailer();

	$body = $contenu;
	$body = eregi_replace("[\]", '', $body);

	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->CharSet="UTF-8";
	$mail->Host = MAIL_SERVER;
	$mail->SMTPDebug = 2;
	$mail->SMTPAuth = true;
	$mail->Port = MAIL_PORT;
	$mail->Username = MAIL_SERVER;
	$mail->Password = MAIL_PASSWORD;
	$mail->SetFrom(MAIL_ADRESSE, TITRE_SITE);
	$mail->AddReplyTo(MAIL_ADRESSE, TITRE_SITE);
	$mail->Subject = $subject;


	$mail->MsgHTML($body);

	foreach($arrayTo as $adresse)
		$mail->AddBCC($adresse, $adresse);

	/**
	 * ENVOI et DEBUG
	*/
	if (ENVOI_MAIL_OK) {
		if (!$mail->Send()) {
			print_r("Mailer Error: " . $mail->ErrorInfo);
		} elseif ($debug) {
			print_r("Message envoy� � :" . print_r($arrayTo));
			print_r($contenu);
			print_r("---------------\n");
		}
	} elseif ($debug) {
		print_r("Simulation de Message envoy� � :" . print_r($arrayTo));
		print_r($contenu);
		print_r("---------------\n");
	}
}
?>