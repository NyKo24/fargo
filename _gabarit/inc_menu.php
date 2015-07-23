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
      
<?php if(!isset($_SESSION["uti_type"])){?>
<li><a href='<?=BASE_URL?>annonce/lister'>Annonce</a></li>
<li><a href='<?=BASE_URL?>categorie/lister'>Categorie</a></li>
<li><a href='<?=BASE_URL?>document_annonce/lister'>Document_annonce</a></li>
<li><a href='<?=BASE_URL?>type_document/lister'>Type_document</a></li>
<li><a href='<?=BASE_URL?>type_utilisateur/lister'>Type_utilisateur</a></li>

<li><a href='<?=BASE_URL?>ville/lister'>Ville</a></li>

<?php }?>
<?php if(isset($_SESSION["uti_type"]) && $_SESSION["uti_type"]==PROFIL_ADMIN){?>
      <li><a href='<?=BASE_URL?>utilisateur/lister'>Utilisateur</a></li>
<?php }?>      
      </ul>
	  <ul class="nav navbar-nav navbar-right">
        <li><a href="<?=BASE_URL?>authentification/deconnexion"><span class="glyphicon glyphicon-log-in"></span> Se déconnecter</a></li>
      </ul>
    </div>
  </div>
</nav>
