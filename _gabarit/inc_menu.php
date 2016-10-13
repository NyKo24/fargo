<?php 
/**
 * Contient le menu du site
 * Ce fichier est générer automatiquement à partir des tables de la base de données
 * 
 * @author Nicolas BORDES <nicolasbordes>
 * 
 * @version 1.0
 */
?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      <a class="navbar-brand" href="#">&nbsp;</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
      	<?php 
      	if (isset($_SESSION["uti_id"])){
      		if ($_SESSION["uti_profil"] == PROFIL_ADMINISTRATEUR){?>
      			<li><a href='<?=BASE_URL?>utilisateur/lister'>Utilisateur</a></li>
      			<li><a href='<?=BASE_URL?>cours/lister'>Cours</a></li>
      			<li><a href='<?=BASE_URL?>formule/lister'>Formule</a></li>
      			<li><a href='<?=BASE_URL?>saison/lister'>Saison</a></li>
      			<li><a href='<?=BASE_URL?>piscine/lister'>Piscine</a></li>
      			<li><a href='<?=BASE_URL?>saison/statistique'>Statistique</a></li>
      			<li><a href='<?=BASE_URL?>utilisateur/motdepasse'>Mot de passe</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gestion financière <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href='<?=BASE_URL?>paiement/recapitulatif'>Récapitulatif</a></li>
                <li><a href="<?=BASE_URL?>paiement/recherche">Rechercher une opération</a></li>
                <li><a href="<?=BASE_URL?>bordereau/bordereau">Générer / rechercher un bordereau</a></li>
              </ul>
            </li>
      			
      		<? } elseif ($_SESSION["uti_profil"] == PROFIL_SUPERADMINISTRATEUR){?>
      			<li><a href='<?=BASE_URL?>utilisateur/lister'>Utilisateur</a></li>
      			<li><a href='<?=BASE_URL?>cours/lister'>Cours</a></li>
      			<li><a href='<?=BASE_URL?>formule/lister'>Formule</a></li>
      			<li><a href='<?=BASE_URL?>saison/lister'>Saison</a></li>
      			<li><a href='<?=BASE_URL?>piscine/lister'>Piscine</a></li>
      			
      			<li><a href='<?=BASE_URL?>contact/lister'>Contact</a></li>
      			<li><a href='<?=BASE_URL?>ecriture_comptable/lister'>Ecriture_comptable</a></li>
      			<li><a href='<?=BASE_URL?>ecriture_gestion/lister'>Ecriture_gestion</a></li>
      			<li><a href='<?=BASE_URL?>etat_operation/lister'>Etat_operation</a></li>
      			<li><a href='<?=BASE_URL?>etat_seance/lister'>Etat_seance</a></li>
      			<li><a href='<?=BASE_URL?>inscription_cours/lister'>Inscription_cours</a></li>
      			<li><a href='<?=BASE_URL?>jour_semaine/lister'>Jour_semaine</a></li>
      			<li><a href='<?=BASE_URL?>maitre_nageur_seance/lister'>Maitre_nageur_seance</a></li>
      			<li><a href='<?=BASE_URL?>niveau_cours/lister'>Niveau_cours</a></li>
      			<li><a href='<?=BASE_URL?>operation/lister'>Operation</a></li>
      			<li><a href='<?=BASE_URL?>operation_etat/lister'>Operation_etat</a></li>
      			<li><a href='<?=BASE_URL?>ouvertur_piscine/lister'>Ouvertur_piscine</a></li>
      			<li><a href='<?=BASE_URL?>paiement/lister'>Paiement</a></li>
      			<li><a href='<?=BASE_URL?>participer_seance/lister'>Participer_seance</a></li>
      			<li><a href='<?=BASE_URL?>preinscription_cours/lister'>Preinscription_cours</a></li>
      			<li><a href='<?=BASE_URL?>seance/lister'>Seance</a></li>
      			<li><a href='<?=BASE_URL?>seance_etat/lister'>Seance_etat</a></li>
      			<li><a href='<?=BASE_URL?>tarif_cours/lister'>Tarif_cours</a></li>
      			<li><a href='<?=BASE_URL?>type_contact/lister'>Type_contact</a></li>
      			<li><a href='<?=BASE_URL?>type_utilisateur/lister'>Type_utilisateur</a></li>
      			<li><a href='<?=BASE_URL?>ville/lister'>Ville</a></li>
      		<?} else if ($_SESSION["uti_profil"] == PROFIL_MAITRENAGEUR)
      		{?>
      			<li><a href='<?=BASE_URL?>seance/presenceMaitreNageur'>Présence</a></li>
      			<li><a href='<?=BASE_URL?>utilisateur/motdepasse'>Mot de passe</a></li>
      		<?php }
      	}?>
        
      </ul>
	  <ul class="nav navbar-nav navbar-right">
       	<?php 
       	if (isset($_SESSION['uti_id'])){
       		if ($_SESSION["uti_profil"] == PROFIL_ADMINISTRATEUR){?>
	       	<li class="dropdown">
	       		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Contacter les adhérents <span class="caret"></span></a>
	       		<ul class="dropdown-menu">
	       			<li><a href="<?=BASE_URL?>contact/sms">Par SMS</a></li>
	       			<li><a href="<?=BASE_URL?>contact/mail">Par mail</a></li>
	       		</ul>
	       	</li>
	       	
	       	<li class="dropdown">
       			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Paramètres <span class="caret"></span></a>
	       		<ul class="dropdown-menu"> 
	       			<li><a href='<?=BASE_URL?>banque/lister'>Banque</a></li>
	       			<li><a href='<?=BASE_URL?>niveau/lister'>Niveau</a></li>
	       			<li><a href='<?=BASE_URL?>methode_paiement/lister'>Methode de paiement</a></li>
					<li><a href='<?=BASE_URL?>vacances/lister'>Vacances</a></li>
				</ul>
			</li>
			
       		<?} else if ($_SESSION["uti_profil"] == PROFIL_SUPERADMINISTRATEUR){?>
       		<li class="dropdown">
       			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Paramètres <span class="caret"></span></a>
	       		<ul class="dropdown-menu">
	       			<li><a href='<?=BASE_URL?>banque/lister'>Banque</a></li>
	       			<li><a href='<?=BASE_URL?>niveau/lister'>Niveau</a></li>
	       			<li><a href='<?=BASE_URL?>methode_paiement/lister'>Methode de paiement</a></li>
	       			<li><a href='<?=BASE_URL?>vacances/lister'>Vacances</a></li>
	       			
	       		</ul>
	       	</li>
       		<?} else if ($_SESSION["uti_profil"] == PROFIL_MAITRENAGEUR)
      		{?>
      			<li><a href='<?=BASE_URL?>seance/presenceMaitreNageur'>Présence</a></li>
      		<?php }		
       	}?>
       	<?php if (isset($_SESSION["uti_id"])){?>
       		<li><a href="<?=BASE_URL?>authentification/deconnexion"><span class="glyphicon glyphicon-log-out"></span> Se déconnecter (<?=ucfirst(strtolower($_SESSION["uti_prenom"]))?>&nbsp;<?=strtoupper($_SESSION["uti_nom"])?>)</a></li>
       	<?}else{?>
        	<li><a href="<?=BASE_URL?>authentification/connexion"><span class="glyphicon glyphicon-log-in"></span> Se connecter</a></li>
      	<? }?>
      </ul>
    </div>
  </div>
</nav>
