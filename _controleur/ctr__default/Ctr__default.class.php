<?php

class Ctr__default extends Controleur {

    public function __construct($a) {
        parent::__construct("_default", $a,"gab_home.php");
    }

    function index() {
    	$this->titre="Acceuil - " . TITRE_SITE;
    	$objAnnonce=new Annonce();
    	$resultAnnonce = $objAnnonce->annonceValide();
    	
    	require BASE_REP . "_gabarit/$this->gabarit";
    }
}

?>