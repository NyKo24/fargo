<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Mot de passe oublié</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="_css/bootstrap-3.3.1/css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="_css/login.css" rel="stylesheet">
	</head>
	<body>
<!--login modal-->
<div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h1 class="text-center">Mot de passe oublié</h1>
		  <h3><?= self::flashMessage()->display(); ?></h3>
      </div>
      <div class="modal-body">
          <form class="form col-md-12 center-block" method="post" action="<?=BASE_URL?>authentification/resetmdp">
            <div class="form-group">
              <input type="text" name="uti_email" class="form-control input-lg" placeholder="Adresse email">
            </div>

            <div class="form-group">
              <button class="btn btn-primary btn-lg btn-block">Obtenir un nouveau mot de passe</button>
            </div>
            
          </form>
      </div>
      <div class="modal-footer">
          <div class="col-md-12">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
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