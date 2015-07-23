<?php
set_time_limit(3000);
class Ctr__generateur extends Controleur {

    static $lprefixe = 3;

    public function __construct($a) {
        parent::__construct("_generateur", $a);
    }

    function magicAllTables() {
        echo "<h1><a href='" . BASE_URL . "'>voir</a></h1>";
        $sql = "show full tables in " . DB_BDD . " where table_type='BASE TABLE'";
        $result = Table::$con->query($sql);
        while ($row = $result->fetch_row())
            $this->magicOneTable($row[0]);
        $this->creerMenu();
    }

    //génération pour une table, eventuellement passée en parametre
    function magicOneTable($table = "") {
        $table = $table == "" ? $_GET["table"] : $table;
        $this->creerClassTable($table);
        $this->creerClassControleur($table);
        $this->creerVueLister($table);
        $this->creerVueEditer($table);
        echo $table . "<br>";
    }

    private function clePrimaire($table){
        $query="show fields from $table";
        $result=Table::$con->query($query);
        while ($row=$result->fetch_assoc())
            if($row["Key"]=="PRI")
                return $row["Field"];
    }
    
    private function creerClassTable($nomTable) {
        $nomClasse = ucfirst($nomTable);
        $nomCle = self::clePrimaire($nomTable);
        $chaine = file_get_contents(BASE_REP . "_controleur/ctr_" . $this->table . "/template/Xxx.class.txt");
        $chaine = str_replace("[nomClasse]", $nomClasse, $chaine);
        $chaine = str_replace("[nomTable]", $nomTable, $chaine);
        $chaine = str_replace("[nomCle]", $nomCle, $chaine);
        file_put_contents(BASE_REP . "_modele/$nomClasse.class.php", $chaine);
    }

    private function creerClassControleur($nomTable) {
        $chaine = file_get_contents(BASE_REP . "_controleur/ctr_" . $this->table . "/template/Ctr_xxx.class.txt");
        if ($nomTable=="utilisateur")
            $chaine = file_get_contents(BASE_REP . "_controleur/ctr_" . $this->table . "/template/Ctr_xxxuti.class.txt");
        $chaine = str_replace("[nomTable]", $nomTable, $chaine);
        @mkdir(BASE_REP . "_controleur/ctr_$nomTable");
        file_put_contents(BASE_REP . "_controleur/ctr_$nomTable/Ctr_$nomTable.class.php", $chaine);
    }

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

    private function creerVueEditer($nomTable) {
        $chaine = file_get_contents(BASE_REP . "_controleur/ctr_" . $this->table . "/template/vue_xxx_editer.txt");
        $chaine = str_replace("[listeChamps]", $this->genererFormulaire($nomTable), $chaine);
        file_put_contents(BASE_REP . "_controleur/ctr_" . $nomTable . "/vue_" . $nomTable . "_editer.php", $chaine);
    }

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

    private function libelle($champ){
        $libelle=strrchr($champ,'_');
        echo $libelle . "<br>";
        $libelle = substr($libelle,1);
        echo $libelle . "<br>";
        return $libelle;
    }
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
                        $prefixe=substr($nom,0,self::$lprefixe);
                        $phpVar = "<?=mhe(\$" . $nom . ")?>";
                        $pref=substr($contrainte['REFERENCED_TABLE_NAME'],0,self::$lprefixe);
                        $pk=$pref."_id";
                        $libel=$pref."_libelle";
                        $sql="select * from ". $contrainte['REFERENCED_TABLE_NAME'];
                        $chaine = $chaine . ""
                                . "<div class='form-group'>\n"
                                . "<label for='$nom'>$libelle</label>\n"
                                . "<select id='$nom' name='$nom'>\n"
                                . "<?=Table::OPTION_from_table('$sql','$pk','$libel','$prefixe"."_id')?>\n"
                                . "</select>\n"
                                . "</div>\n";
                    } else {
                        $libelle = self::libelle($nom);
                        $phpVar = "<?=mhe(\$" . $nom . ")?>";
                        $chaine = $chaine . ""
                                . "<div class='form-group'>\n"
                                . "<label for='$nom'>$libelle</label>\n"
                                . "<input id='$nom' name='$nom' type='text' size='50' value='$phpVar'  class='form-control' />\n"
                                . "</div>\n";
                    }   
                }
               
            }
        return $chaine;
    }

    function thListeChamp($ar) {
        $s = "";
        foreach ($ar as $valeur)
            $s.="\n\t\t\t<th>$valeur</th>";
        return $s;
    }

    function tdListeValeur($ar) {
        $s = "";
        foreach ($ar as $valeur)
            $s.="\n\t\t\t<td><?=\$row['" . $valeur . "']?></td>";
        return $s;
    }
    
}



?>