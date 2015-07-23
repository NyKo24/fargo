<?php

class Table {

    /**
     * @var int $cle Nom du champ PRIMARY KEY dans la table
     * @var string $table Nom de la table
     * @var array $data Tableau où les clés associatives sont les noms des champs de la table
     */
    public $cle;
    public $table;
    public $data = array();
    public static $con = null;

    public function __construct($table, $cle) {
        $this->table = $table;
        $this->cle = $cle;
        $this->initData();
    }

    /**
     * Quand création d'un nouvel objet vide : 
     * initialise le nom des index dans le tableau $data,
     *  les valeurs associées étant vides
     */
    public function initData() {
        $result = self::$con->query("SHOW COLUMNS FROM " . $this->table);
        while ($row = $result->fetch_row())
            $this->data[$row[0]] = "";
        $this->data[$this->cle] = 0;
    }
    
    /**
     * Renvoie le nombre d'enregistrement de $this->table;
     */

    public function count(){
    	$result = self::$con->query("select count($this->cle) as nb from " . $this->table);
    	$row = $result->fetch_assoc();
    	return $row["nb"];
    }
    
    /*
    * Retourn la valeur du champ $champ de l'enregistrment $id de la table $this->table
    * @param int $id : identifiant de l'enregistrement
    * @param string $champ : nom du champ
    * @return string / int : valeur du champ 
    */
    public function valeur($id,$champ){
        $query="select $champ from $this->table where $this->cle=$id";
        $result=self::$con->query($query);
        $row=$result->fetch_assoc();
        return $row[$champ];
    }


    public function chargerDepuisBdd($id) {
        $sql = "select * from $this->table where $this->cle=$id";
        $result = self::$con->query($sql);
        if ($result and $result->num_rows == 1)
            $this->data = $result->fetch_assoc();
        else
            $this->initData();
    }

    public function chargerDepuisTableau($tableau) {
        foreach ($this->data as $index => $valeur)
            if (isset($tableau[$index]))
                $this->data[$index] = $tableau[$index];
            elseif (@is_null($tableau[$index])) 
                $this->data[$index]=NULL;
    }

    public function lister($page=null) {
    	if($page)
    		$sql = "select * from $this->table $page";
    	else 	
       		$sql = "select * from $this->table";
        return self::$con->query($sql);
    }

    public function getListe() {
        $sql = "select * from $this->table";
        $result = self::$con->query($sql);
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     *  Sauve l'objet courant dans la BDD
     */
    public function sauver() {
        //copie du tableau
        $tab = $this->data;
        //Préparation de la liste des champs
        $listField = "(" . implode(",", array_keys($tab)) . ")";
        //extraction de la clé
        $id = $tab[$this->cle];
        //suppression du champ clé du tableau
        unset($tab[$this->cle]);
        //valeurs enveloppées de quote
        $tab = array_map("Table::myQuote", $tab);

        //Création
        if ($id == 0)
            $requete = "insert into $this->table " . $listField . "  values (''," . implode(",", $tab) . ")";
        else
            $requete = "update $this->table set " . implode(",", self::myPrepareUpdate($tab)) . " where $this->cle=$id";

        self::$con->query($requete);

        if ($id == 0)
            $this->data[$this->cle] = self::$con->insert_id;

        if (self::$con->errno) {
            echo "ERREUR : $requete : " . self::$con->error;
            return false;
        } else
            return true;
    }

    /**
     * Supprime un enregistrement
     * @param int $id
     */
    public function supprimer($id) {
        if ($id != 0) {
            $requete = "delete from $this->table where $this->cle = $id";
            self::$con->query($requete);
        }
    }

    /**
     * Echappe les quotes et enveloppe de quotes, sauf si nul
     * @param mixed $valeur
     * @return string
     */
    public static function myQuote($valeur) {
        if (is_null($valeur))
            return "null";
        else
            return "'" . self::$con->real_escape_string($valeur) . "'";
    }

    /**
     * Crée un tableau Nom_champ=Valeur
     * @param array $tab
     * @return array
     */
    public static function myPrepareUpdate($tab) {
        foreach ($tab as $index => $valeur)
            $tab2[] = "$index=$valeur";
        return $tab2;
    }

    // Renvoie un objet de connexion à la BD en initialisant la connexion au besoin
    public static function getCon($db_server, $db_user, $db_pwd, $db_bdd) {
        if (self::$con == null) {
            self::$con = new mysqli($db_server, $db_user, $db_pwd, $db_bdd);
            //self::$con->set_charset("utf8");

            if (self::$con->connect_errno)
                echo "Echec lors de la connexion à MySQL : " . self::$con->connect_error;
        }
        return self::$con;
    }

    static function getChamps($table) {
        $sql = "select * from $table";
        $result = self::$con->query($sql);
        $ar = array();
        while ($champ = $result->fetch_field())
            $ar[] = $champ->name;
        return $ar;
    }

    // affichage HTML d'une table
    public function affiche_liste($sql) {
        $result = self::$con->query($sql);
        $table = $this->table;
        $pk = $this->cle;
        ?>
        <H1>table <?= $table ?></H1>
        <table border='1'>
            <caption><a href="<?= BASE_URL ?><?= $table ?>/editer/<?= $pk ?>/0">Ajouter</a><caption>
                    <tr>
                        <th>Edit</th>
                        <?php
                        //Affichage des noms de champs
                        //if ($result)
                        foreach ($result->fetch_fields() as $champ)
                            echo "<th>" . $champ->name . "</th>";
                        ?>
                        <th>Suppr</th>
                    </tr>
                    <?php
                    //affichage de chaque ligne de la table
                    //if ($result)
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><a href="<?= BASE_URL ?><?= $table ?>/editer/<?= $pk ?>/<?= $row[$pk] ?>">Editer</a></td>
                            <?php
                            foreach ($row as $cle => $item)
                                echo "<td>" . mhe($item) . "</td>";
                            ?>
                            <td><a href="<?= BASE_URL ?><?= $table ?>/supprimer/<?= $pk ?>/<?= $row[$pk] ?>">Supprimer</a></td>
                        </tr>
                    <?php } //FIN WHILE  ?>
                    </table>
                    <?php
                }

                //FIN FUNCTION affiche
                //Affiche une liste déroulante avec des données issues d'une requete
                public static function OPTION_from_table($sql, $pk, $libelle, $idsel) {
                    $result = self::$con->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $sel = ($idsel == $row[$pk]) ? " selected " : "";
                        ?>
                        <option value="<?= $row[$pk] ?>" <?= $sel ?> ><?= mhe($row[$libelle]) ?></option>
                        <?php
                    } //FIN WHILE
                }

public static function CHECKBOX_from_table($sql, $pk, $libelle, $name, $idsel="") {
                    $result = self::$con->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $sel='';
                        if(is_array($idsel)){
                            foreach($idsel as $cle => $valeur){
                                if($row[$pk]==$valeur)
                                    $sel=" checked ";
                            }
                        }else
                            $sel = ($idsel == $row[$pk]) ? " checked " : "";
                        ?>
                                <input value="<?= $row[$pk] ?>" type="checkbox" name="<?=$name?>[]" id="<?=$row[$libelle]?>" <?=$sel?> >
                                <label for="<?=$row[$libelle]?>"><?=$row[$libelle]?></label>
                                <br>
                                        
                                        <?php
                                    } //FIN WHILE
                                }
               
                // FUN FUNC option
            }

            //FIN CLASS Table
            ?>