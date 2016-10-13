<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Connexion</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="<?=BASE_URL?>_css/bootstrap-3.3.1/css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="<?=BASE_URL?>_css/login.css" rel="stylesheet">
	</head>
	<body>
<!--login modal-->
<div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          
          <h1 class="text-center">Connexion</h1>
          <p class="text-center">
          	<img src="<?=BASE_URL?>_image/logo.png">
          </p>
          <h3><?= self::flashMessage()->display(); ?></h3>
      </div>
      <div class="modal-body">
          <form class="form col-md-12 center-block"  method="post" action="<?=BASE_URL?>authentification/connexion">
            <div class="form-group">
              <input type="text" name="login" class="form-control input-lg" placeholder="Identifiant">
            </div>
            <div class="form-group">
              <input type="password" name="mdp" class="form-control input-lg" placeholder="Mot de passe">
            </div>
            <div class="form-group">
              <button class="btn btn-primary btn-lg btn-block">Connexion</button>
              <span class="pull-right"><a href="<?=BASE_URL?>authentification/mdpoublie">Mot de passe oublié</a></span>
            </div>
            <div class="form-group">
            	<div class="checkbox">
			    	<label>
			      		<input name="souvenir" value="oui" type="checkbox"> Se souvenir de moi
			    	</label>
		  		</div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
          <div class="col-md-12">
          	<p>De <a href="http://www.twitter.com/NyKo24">Nicolas BORDES</a> (c) 2016</p>
		  </div>	
      </div>
  </div>
  </div>
</div>
	<!-- script references -->
		<script src="_composants/jquerry/dist/jquery.min.js"></script>
		<script src="_js/bootstrap.min.js"></script>
	</body>
</html>