<?php
use League\Flysystem\Filesystem;
use League\Flysystem\File;
use League\Flysystem\Adapter\Local;
/**
 * Class générique contrôlleur
 * 
 * Cette class est la classe mère de toutes classe de type contrôlleur, elle dispose des méthodes générique de gestion du CRUB pour n'importe quelles tables
 * 
 * @author Nicolas BORDES <nicolasbordes@me.com>
 * @author Gilles LEVY
 * 
 * @version 1.7
 * 
 * @package Framework\Controleur
 *
 */

class Controleur {

    //pour un CRUD, $table représente la table gérer et $className la class associée
    /**
     * Nom de la table
     * @var string
     */
	public $table;
    
	/**
	 * Nom de la classe modèle
	 * @var string
	 */
    public $className;
    
    /**
     * Titre de la page (balise HTML title)
     * @var string
     */
    public $titre;
    
    /**
     * Nom de la méthode à appeler
     * @var string
     */
    public $action;
    
    /**
     * Nom de la vue à charger
     * @var string
     */
    public $vue;
    
    /**
     * Nom du gabarit à utiliser
     * @var string
     */
    public $gabarit;

    /**
     * Gestionnaire de fichier
     * @var File
     */
    static public $fileManager = null;
    
    static public $flashMsg = null;
    
    /**
     * Set les valeurs des attributs $table, $action, $gabarit puis appel l'action
     * @param string $t nom de la table
     * @param string $a nom la méthode (action) à appeler
     * @param [string] $gabarit nom du gabarit à utiliser
     */
    public function __construct($t, $a = null, $gabarit = "standard.php") {
        $this->table = $t;
        $this->className = ucfirst($t);
        $this->action = $a;
        $this->titre = "$this->action $this->table";
        $this->gabarit = $gabarit;
        $this->vue = BASE_REP . "_controleur/ctr_" . $this->table . "/" . "vue_" . $this->table . "_" . $this->action . ".php";
        if(!is_null($a))
        	$this->$a();
    }

    /**
     * Lister tous les enregistrement de $this->table dans une vue HTML
     */
    function lister() {
    	//isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        $result=$obj->lister();
        require BASE_REP . "_gabarit/$this->gabarit";
    }

    /**
     * Affiche un formulaire d'édition en pré-remplissant les valeur de l'enregistrement $_GET["id"], affiche un formulaire sinon 
     */
    function editer() {
    	//isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        //Parametre : identifiant
        $id = (isset($_GET["id"])) ? $_GET["id"] : 0;
        $this->titre .= " : id=$id";

        $obj->chargerDepuisBdd($id);
        extract($obj->data);
        require BASE_REP . "_gabarit/$this->gabarit";
    }

    /**
     * Sauvegarder le contenu de du formulaire dans la table $this->table
     */
    function sauver() {
    	//isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        $obj->chargerDepuisTableau($_POST);
        if ($obj->sauver())
            header("location:" . BASE_URL . "$this->table/lister");
    }

    /**
     * Supprimer l'enregistrement $_GET["id"] de la table $this->table puis redirige sur la vue lister de la table $this->table
     */
    function supprimer() {
    	//isAuth(array(PROFIL_ADMIN,PROFIL_MODO));
        $obj = new $this->className();
        $id = (isset($_GET["id"])) ? $_GET["id"] : 0;
        $obj->supprimer($id);
        header("location:" . BASE_URL . "$this->table/lister");
    }

    /**
     * Renvoi sur la page index
     */
    function index() {
        require BASE_REP . "_gabarit/$this->gabarit";
    }
    
    /**
     * Affiche entre balise HTML <pre> un print_r de $x
     * @param mixed $x variable à afficher
     */
    static function debug($x){
    	echo "<pre>";
    	print_r($x);
    	echo "</pre>";
    }

    /**
     * Affiche la version crypée de la chaine de caractère contenu dans $_GET["chaine"]
     */
    public function crypte(){
    	echo cryptage($_GET["chaine"]);
    }
    
    /**
     * Singleton : retourne l'objet pour gérer les messages flash;
     */
    public function flashMessage()
    {
    	if (self::$flashMsg === null)
    		self::$flashMsg = $msg = new \Plasticbrain\FlashMessages\FlashMessages();
    	return self::$flashMsg;
    }
    
    /**
     * Retourne la requeute LIMIT X,Y pour la pagination en fonction de $resultTotal et $currentPage
     * @param int $resultTotal nombre total d'enregistrement dans la table
     * @param int $currentPage âge courrante
     */
    public function calculepagination($resultTotal,$currentPage){
    	//nombre de page total
    	$nbPageTotal = ceil($resultTotal / NB_RESULT_PAGE);
    	
    	if($currentPage){
    		//si le numérode la page est plus grand que le nombre de page total on renvoie a la 1er page
    		$currentPage = ($currentPage > $nbPageTotal) ? header("location:".BASE_URL.$this->table."/lister/page/1") : $currentPage;
    	
    		if($currentPage > 1)
    			$query = " LIMIT " . ($currentPage - 1) * NB_RESULT_PAGE ."," . NB_RESULT_PAGE;
    			else
    				$query = "LIMIT " . "0"."," . NB_RESULT_PAGE;
    	}else{
    		$query = "LIMIT " . "0"."," . NB_RESULT_PAGE;
    	}
    	
    	return $query;
    }
    
    /**
     * Genère automatiquement le code HTML de la pagination
     * @param int $resultTotal
     * @param int $curentPage
     */
    public function generationPagination($resultTotal, $curentPage,$action = null){
    	//nombre de page total
    	$nbPageTotal = ceil($resultTotal / NB_RESULT_PAGE);
    	
    	$action = (is_null($action)) ? $this->action : $action;
    	?>
    	
    	<nav>
		  <ul class="pagination">
		    <li class="<?= (1 == $curentPage) ? " disabled " : "" ?>">
		      <a data-page="<?=$curentPage-1?>" href="<?=BASE_URL.$this->table."/".$action?>/page/<?=$curentPage-1?>" aria-label="Précédent">
		        <span  aria-hidden="true">&laquo;</span>
		      </a>
		    </li>
		    <?php for ($i=1; $i<= $nbPageTotal;$i++){?>
		    	<li class="<?= ($i == $curentPage) ? " active " : "" ?>"><a  href="<?=BASE_URL.$this->table."/".$action?>/page/<?=$i?>" data-page="<?=$i?>"><?=$i?></a></li>
		    <?php }?>
		    <li class="<?= ($nbPageTotal == $curentPage) ? " disabled " : "" ?>>
		      <a data-page="<?=$curentPage+1?>" href="<?=BASE_URL.$this->table."/".$action."/page/"?><?=$curentPage+1?>" aria-label="Suivant">
		        <span aria-hidden="true">&raquo;</span>
		      </a>
		    </li>
		  </ul>
		</nav>
		
		<?php
		
    }
    
    /**
     * Met au format "JJ-MM-YYYY" ou "JJ-MM-YYYY HH:MM:SS" une date au format MySQL (YYYY-MM-DD) ou (YYYY-MM-DD HH:II:SS)
     * @param string $dateSQL
     * @param string $delimiteur
     * @return string
     */
    public function formatDate($dateSQL,$delimiteur = "/"){
    	if (is_null($dateSQL) OR empty($dateSQL))
    		return null;
    	$array = explode("-", $dateSQL);
    	if (strpos($array[2]," ")){
    		$array2 = explode(" ", $array[2]);
    		$array3 = explode(":", $array2[1]);
    		return $array2[0] . $delimiteur . $array[1] . $delimiteur . $array[0] . " " . $array3[0] . ":" . $array3[1] . ":" . $array3[2];
    		
    	} else {
    		return $array[2] .$delimiteur. $array[1].$delimiteur. $array[0];
    	}
    }	
    
    /**
     * Historisation de l'échange par mail / SMS
     * @param string $contenu Contenu du message
     * @param int $utilisateur_id Identifiant de l'utilisateur à qui s'adresse le message
     * @param int $type Type de message
     * @param string $path Chemin absolu où enregistrer le fichier
     * @return boolean
     */
    protected function sauvegarde_contact($contenu, $utilisateur_id,$type,$path = null){
    	/*
    	 * $type = 2 == email
    	 * $type = 1 == SMS
    	 */
    	if (is_null($path))
    		$path = 'mail/envoye/';	
    	
    	$objContact = new Contact();

    	$objContact->utilisateur = $utilisateur_id;
    	$objContact->contenu = $contenu;
    	$objContact->type = $type;
    	
    	if ($type == 2){
    		$filename=$path.uniqid().".html";
    		$filesystem = Controleur::getFileManager();
    		$filesystem->write($filename,$contenu);
    		$objContact->fichier = str_replace(BASE_REP, "", $filename);
    	} else if ($type == 1){
    		$objContact->fichier = null;
    		
    	}
    	
    	$objContact->date = date("Y-m-d H:i:s");
    	
    	if($objContact->sauver())
    		return true;
    	else
    		return false;
    }
    
    /**
     * Génère des balises option pour un select en HTML
     * @param int $start
     * @param int $max
     * @param int $pas
     */
    public function genereOption($start = 8,$max = 23, $pas = 1, $check = null){
    	for ($i = $start; $i < $max ; $i+=$pas ){
    		if (is_null($check))
    			echo "<option value='$i'>$i</option>";
    		else {
    			if($check == $i)
    				echo "<option selected value='$i'>$i</option>";
    			else
    				echo "<option value='$i'>$i</option>";
    		}
    	}
    }
    
  /**
   * Génère les balises options à partir d'un tableau : les clés sont les value des option
   * @param array $array
   * @param string $check
   */
    public function genereOptionArray($array,$check = null){
    	foreach ($array as $num => $libelle){
    		if (!is_null($check) && $check == $num){
    			echo "<option value='$num' selected >$libelle</option>";
    		}else{
    			echo "<option value='$num'>$libelle</option>";
    		}
    	}	
    }
    
    /**
     * Met à jour le champ $_GET["field"] de l'enregistrement $_GET["id"] de la table $_GET["table"] avec la valeur $_POST["value"]
     * Retourne la valeur modifiée
     */
    public function ajax_updateField(){
    	$json = array();
    	
    	if (isset($_GET["id"])){
    		if (isset($_GET["table"])){
    			if (isset($_GET["field"])){
    				if (isset($_POST["value"])){
    					
    					extract($_POST);
    					extract($_GET);
    					$table = ucfirst(strtolower($table));
    					
    					$objTable = new $table();
    					
    					$objTable->chargerDepuisBdd($id);
    					
    					$objTable->$field = $value;
	    					
    					if($objTable->sauver()){
    						die($value);
    					}else{
    						$json["etat"]="erreur";
    						$json["erreur"]="Impossible de mettre à jour le champ";
    						die(json_encode($json));
    					}
    					
    				} else {
    					$json["etat"]="erreur";
    					$json["erreur"]="Valeur du champ manquante";	
    					die(json_encode($json));
    				}
    			} else {
    				$json["etat"]="erreur";
    				$json["erreur"]="Nom du champ manquant";	
    				die(json_encode($json));
    			}
    		} else {
    			$json["etat"]="erreur";
    			$json["erreur"]="Nom de la table manquant";	
    			die(json_encode($json));
    		}
    	}else {
    		$json["etat"]="erreur";
    		$json["erreur"]="Identifiant de l'enregistrement absent";
			die(json_encode($json));
    	}
    	
    	
    }
    

    /**
     * Supprime la photo (fichier) qui a pour URI $path
     * @param string $path
     * @return boolean
     */
    protected function removePhotoFile($path){
    	if (file_exists($path)){
    		if (@unlink($path))
    			return true;
    		else
    			return false;
    	}else{
    		return false;
    	}
    }
    
    /**
     * Supprime l'image $fieldPhoto de la table $table
     * @param string $fieldPhoto
     * @param string $table
     * @return boolean
     */
    protected function removePhotoBdd($fieldPhoto, $table){
    	$table = ucfirst(strtolower($table));
    	$obj = new $table();
    	$obj->$fieldPhoto = null;
    	if ($obj->sauver()){
    		return true;
    	}else{
    		return false;
    	}
    
    }

    /**
     * Retourne un nom unique de fichier avec son extension initiale
     * @param string $name
     * @return string
     */
    private function renamePhoto($name){
    	$info = new SplFileInfo($name);
    	$extension = "." . $info->getExtension();
    	 
    	return substr(uniqid(), -6,6) . $extension;
    }

    /**
     * Vérifier que le dossier $path est créé, sinon il le créé
     * @param string $path
     * @return boolean
     */
    private function checkDossierPhoto($path){
    	if (file_exists($path))
    		return true;
    	else{
    		if(mkdir($path,0644))
    			return true;
    		else
    			return false;
    	}
    }

    /**
     * Renome la photo uploader, la place dans le dossier $path puis retourne le nom du fichier modifier
     * @param string $photoFile
     * @param string $path
     * @return string
     */
    protected function traitementPhoto($photoFile,$path){
    	extract($photoFile);
    	$name = $this->renamePhoto($name);
    	$filesystem = Controleur::getFileManager();
    	$filesystem->write($path."/".$name, Controleur::getFileContent($photoFile["tmp_name"]));
    	return $name;
    }
    
    static public function sendContent($contents,$filename)
    {
    	header('Content-Type: application/octet-stream');
    	header('Content-Transfer-Encoding: Binary');
    	header('Content-disposition: attachment; filename="' . $filename . '"');
    	echo $contents;
    }
    
    /**
     * Retoure une date SQL au format YYYY-MM-DD à partir d'une date au format fraçaise
     * @param string $normalDate
     * @param string $delimiteur
     * @return string
     */
    protected function createDateSQL($normalDate,$delimiteur = "/"){
    	$array = explode($delimiteur, $normalDate);
    	
    	$dateSQL = $array[2]."-".$array[1]."-".$array[0];
    	return $dateSQL;
    }
    
    /**
     * Lit puis retourne le contenu du fichier $file
     * @param string $file
     * @return string
     */
    static public function getFileContent($file)
    {
    	$handle = fopen($file, "r");
    	$contents = fread($handle, filesize($file));
    	fclose($handle);
    	return $contents;
    }
    
    /**
    * Singleotn retournant le gestionnaire de fichier
    */
    static public function getFileManager()
    {
    	if (is_null(self::$fileManager))
    	{
    		$adapter = new Local(BASE_REP.'_data/');
    		self::$fileManager = new Filesystem($adapter);
    	}
    	
    	return self::$fileManager;
    }
}

?>