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
<nav class="navbar navbar-inverse">
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
        [menu]
      </ul>
	  <ul class="nav navbar-nav navbar-right">
        <li><a href="<?=BASE_URL?>authentification/connexion"><span class="glyphicon glyphicon-log-in"></span> Se connecter</a></li>
      </ul>
    </div>
  </div>
</nav>
