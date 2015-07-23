<?php

class Controleur {

    //pour un CRUD, $table représente la table gérer et $className la class associée
    public $table;
    public $className;
    public $titre;
    public $action;
    public $vue;
    public $gabarit;

    public function __construct($t, $a, $gabarit = "standard.php") {
        $this->table = $t;
        $this->className = ucfirst($t);
        $this->action = $a;
        $this->titre = "$this->action $this->table";
        $this->gabarit = $gabarit;
        $this->vue = BASE_REP . "_controleur/ctr_" . $this->table . "/" . "vue_" . $this->table . "_" . $this->action . ".php";
        $this->$a();
    }

    function lister() {
    	isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        $result=$obj->lister();
        require BASE_REP . "_gabarit/$this->gabarit";
    }

    function editer() {
    	isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        //Parametre : identifiant
        $id = (isset($_GET["id"])) ? $_GET["id"] : 0;
        $this->titre .= " : id=$id";

        $obj->chargerDepuisBdd($id);
        extract($obj->data);
        require BASE_REP . "_gabarit/$this->gabarit";
    }

    function sauver() {
    	isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        $obj->chargerDepuisTableau($_POST);
        if ($obj->sauver())
            header("location:" . BASE_URL . "$this->table/lister");
    }

    function supprimer() {
    	isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        $id = (isset($_GET["id"])) ? $_GET["id"] : 0;
        $obj->supprimer($id);
        header("location:" . BASE_URL . "$this->table/lister");
    }

    function index() {
        require BASE_REP . "_gabarit/$this->gabarit";
    }
    
    function debug($x){
    	echo "<pre>";
    	print_r($x);
    	echo "</pre>";
    }

    function crypte(){
    	echo cryptage($_GET["chaine"]);
    }
}

?>