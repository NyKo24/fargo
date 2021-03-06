<?php

set_time_limit(3000);
/**
 * Générateur de code
 * 
 * Génére pour chaque table de la base de donnée 
 * Un dossier Controlleur contenant le controlleur, la vue pour lister et modifier les données
 * La class modèle associé à cette table 
 * 
 * @author Gilles LEVY
 * @author Nicolas BORDES <nicolasbordes@me.com>
 * @package Controleur\
 * @version 1.7
 */
class Ctr__generateur extends Controleur {

	/**
	 * Taille du préfix de chaque champ de la table
	 * @var int
	 */
    static $lprefixe = 3;

    /**
     * Constructeur, appelant le constructeur parent
     * @param string $a nom de la méthode (action) à appeler
     */
    public function __construct($a) {
        parent::__construct("_generateur", $a);
    }

    /**
     * Génère l'ensemble des fichiers du CRUD pour chaque tablea de la base de donnée
     */
    function magicAllTables() {
        echo "<h1><a href='" . BASE_URL . "'>voir</a></h1>";
        $sql = "show full tables in " . DB_BDD . " where table_type='BASE TABLE'";
        $result = Table::$con->query($sql);
        while ($row = $result->fetch_row())
            $this->magicOneTable($row[0]);
        $this->creerMenu();
    }

    /**
     * Genère les fichiers du CRUD pour la table $_GET["table"]
     * @param string $table nom de la table
     */
    function magicOneTable($table = "") {
        $table = $table == "" ? $_GET["table"] : $table;
        $this->creerClassTable($table);
        $this->creerClassControleur($table);
        $this->creerVueLister($table);
        $this->creerVueEditer($table);
        echo $table . "<br>";
    }

    /**
     * Retourne le nom du champ clé primaire de la $table
     * @param string $table nom de la table
     * @return string nom du champ clé primaire
     */
    private function clePrimaire($table){
        $query="show fields from $table";
        $result=Table::$con->query($query);
        while ($row=$result->fetch_assoc())
            if($row["Key"]=="PRI")
                return $row["Field"];
    }
    
    /**
     * Génère la class modele pour la table $nomTable
     * @param string $nomTable nom de la table
     */
    private function creerClassTable($nomTable) {
        $nomClasse = ucfirst($nomTable);
        $nomCle = self::clePrimaire($nomTable);
        $chaine = file_get_contents(BASE_REP . "_controleur/ctr_" . $this->table . "/template/Xxx.class.txt");
        $chaine = str_replace("[nomClasse]", $nomClasse, $chaine);
        $chaine = str_replace("[nomTable]", $nomTable, $chaine);
        $chaine = str_replace("[nomCle]", $nomCle, $chaine);
        file_put_contents(BASE_REP . "_modele/$nomClasse.class.php", $chaine);
    }

    /**
     * Génère la class controleur de la table $nomTable
     * @param string $nomTable nom de la table
     */
    private function creerClassControleur($nomTable) {
        $chaine = file_get_contents(BASE_REP . "_controleur/ctr_" . $this->table . "/template/Ctr_xxx.class.txt");
        if ($nomTable=="utilisateur")
            $chaine = file_get_contents(BASE_REP . "_controleur/ctr_" . $this->table . "/template/Ctr_xxxuti.class.txt");
        $chaine = str_replace("[nomTable]", $nomTable, $chaine);
        @mkdir(BASE_REP . "_controleur/ctr_$nomTable");
        file_put_contents(BASE_REP . "_controleur/ctr_$nomTable/Ctr_$nomTable.class.php", $chaine);
    }

    /**
     * Genère la vue HTML qui va lister les enregistrements de la table pour la table $nomTable
     * @param string $nomTable nom de la table
     */
    private function creerVueLister($nomTable) {
        $chaine = file_get_contents(BASE_REP . "_controleur/ctr_" . $this->table . "/template/vue_xxx_lister.txt");
        $chaine = str_replace("[nomTable]", $nomTable, $chaine);
        $nomCle = self::clePrimaire($nomTable);
        $chaine = str_replace("[nomCle]", $nomCle, $chaine);
        $thnc=$this->thListeChamp(Table::getChamps($nomTable));
        $chaine = str_replace("[thListeChamps]", $thnc, $chaine);
        $tdlv=$this->tdListeValeur(Table::getChamps($nomTable));
        $chaine = str_replace("[tdListeValeur]", $tdlv, $chaine);
        file_put_contents(BASE_REP . "_controleur/ctr_" . $nomTable . "/vue_" . $nomTable . "_lister.php", $chaine);
    }

    /**
     * Genère la vue HTML qui va modifier les enregistrements de la table pour la table $nomTable
     * @param string $nomTable nom de la table
     */
    private function creerVueEditer($nomTable) {
        $chaine = file_get_contents(BASE_REP . "_controleur/ctr_" . $this->table . "/template/vue_xxx_editer.txt");
        $chaine = str_replace("[listeChamps]", $this->genererFormulaire($nomTable), $chaine);
        file_put_contents(BASE_REP . "_controleur/ctr_" . $nomTable . "/vue_" . $nomTable . "_editer.php", $chaine);
    }

    /**
     * Génère le menu du site en fonction des tables de la base de donnée
     */
    private function creerMenu() {
        $chaine = file_get_contents(BASE_REP . "_controleur/ctr_" . $this->table . "/template/inc_menu.php");
        $sql = "show tables";
        $result = Table::$con->query($sql);
        $menu = "";
        while ($row = $result->fetch_row())
            $menu = $menu . "<li><a href='<?=BASE_URL?>" . $row[0] . "/lister'>" . ucfirst($row[0]) . "</a></li>\n";
        $chaine = str_replace("[menu]", $menu, $chaine);
        file_put_contents(BASE_REP . "_gabarit/inc_menu.php", $chaine);
    }

    /**
     * Retourne le nom du champ sans le préfix
     * @param unknown $champ nom du champ complet
     * @return string libelle du champ sans le prefix
     */
    private function libelle($champ){
    	if(strrchr($champ,'_')){
    		$champ=strrchr($champ,'_');
    		$champ = substr($libelle,1);
    	}
        echo $champ . "<br>";
        return $champ;
    }
    
    /**
     * Génère un formulaire de création / modification d'un enregistrement pour la table $nomTable
     * @param string $nomTable nom de la table
     * @return string chaine contenant le code HTML du formulaire
     */
    private function genererFormulaire($nomTable) {
        $chaine = "";
        $nomCle = self::clePrimaire($nomTable);
        $result = Table::$con->query("SHOW COLUMNS FROM $nomTable");
       
        while ($row = $result->fetch_row()) {
            echo "<pre>";
            print_r($row);
            echo "</pre>";
           
            $nom = $row[0];
            $resultContrainte=Table::$con->query("SELECT * FROM information_schema.key_column_usage WHERE table_name =  '$nomTable' and CONSTRAINT_SCHEMA='".DB_BDD."' and COLUMN_NAME='$nom'");
            if ($nomCle != $nom) {
                    if($contrainte=$resultContrainte->fetch_assoc()){
                        $libelle = self::libelle($nom);
                        //$prefixe=substr($nom,0,self::$lprefixe);
                        $phpVar = "<?=mhe(\$" . $nom . ")?>";
                        $pref=substr($contrainte['REFERENCED_TABLE_NAME'],0,self::$lprefixe);
                        $pk=$pref."_id";
                        $libel=$pref."_libelle";
                        $sql="select * from ". $contrainte['REFERENCED_TABLE_NAME'];
                        $chaine = $chaine . ""
                                . "\t <div class='form-group'>\n"
                                . "\t\t <label for='$nom'>$libelle</label>\n"
                                . "\t\t <select id='$nom' name='$nom'>\n"
                                . "\t\t\t <?=Table::OPTION_from_table('$sql','$pk','$libel','id')?>\n"
                                . "\t\t </select>\n"
                                . "\t </div>\n\n";
                    } else {
                        $libelle = self::libelle($nom);
                        $phpVar = "<?=mhe(\$" . $nom . ")?>";
                        $chaine = $chaine . ""
                                . "\t<div class='form-group'>\n"
                                . "\t\t<label for='$nom'>$libelle</label>\n"
                                . "\t\t<input id='$nom' name='$nom' type='text' size='50' value='$phpVar'  class='form-control' />\n"
                                . "\t</div>\n\n";
                    }   
                }
               
            }
        return $chaine;
    }

    /**
     * Génère une chaine HTML contenant les valeur de $ar entre baslise <th>
     * @param array $ar tableau cotenant la liste des champs de la table
     * @return string chaine contenant le code HTML
     */
    private function thListeChamp($ar) {
        $s = "";
        foreach ($ar as $valeur)
            $s.="\n\t\t\t\t<th>$valeur</th>";
        return $s;
    }

    /**
     * Génère une chaine HTML contenant les valeur de $ar entre baslise <td>
     * @param array $ar tableau cotenant la liste des champs de la table
     * @return string chaine contenant le code HTML
     */
    private function tdListeValeur($ar) {
        $s = "";
        foreach ($ar as $valeur)
            $s.="\n\t\t\t\t<td><?=\$row['" . $valeur . "']?></td>";
        return $s;
    }
    
}



?>