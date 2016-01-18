<?php
include_once("../master_db.php");
$db = masterDB::getDB();

class UniteDeCorrection
{
	// CONSTANTES
	
	const TIMEZONE = "Europe/London"; // Date GMT
	const IDMAX = 2147483646; // 2147483647 = 2^(31) = la taille max d'un int
	const STRUCTURE = array('Partie','Exercice','Question','Sous-question');
	
	// ATTRIBUTS
	
	// Uploadés
	private $_idPere = "null";
	private $_data='';
	private $_id = "null";
	private $_idFils = array();
	private $_niveau = 0; // Les niveaux s'incrémentent à mesure que l'unité de correction est basse dans l'arbre
	private $_note = -1; // La note attribuée suite à la correction -1 si l'unitée n'est pas corrigée
	private $_noteMax = 0; // La note maximale que l'on peut obtenir (définie par le barême)
	private $_dateModif = "";// La date, en format DD/MM/YYYY h:m:s, de dernière modification
	private $_idCorrecteur = -1; // Ne sera égal à un ID que pour les plus petites udc
	
	// Non uploadés
	private $_pere = NULL;// DEPRECATED // Référence vers le père
	private $_fils = array(NULL);// DEPRECATED // Référence vers les fils
	
	// CONSTRUCTEURS
	/* DEPRECATED __________________________________________________________________________________________
	
	public function UniteDeCorrection() // DEPRECATED // Constructeur de base de l'unité de correction
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s');
		$_id = getAvailableId(); // interaction avec le serveur
		if($_id == "null") // On n'a plus d'ID disponible (dépassé le nombre maximal d'ID)
		{
			echo "Pas d'ID disponible <br>";
		}
	}
	
	public function UniteDeCorrection($idPere) // Constructeur appelé au moment de la création d'un fils
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s');
		
		$_id = getAvailableId(); // interaction avec le serveur
		if($_id == "null") // On n'a plus d'ID disponible (dépassé le nombre maximal d'ID)
		{
			echo "Pas d'ID disponible <br>";
		}
		
		$_idPere = $idPere;
		$_niveau = $_idPere->getNiveau() + 1;
		
		$_pere = getUnitById($_idPere);
		$_pere.addSon($this);
		
	}

	
	public function UniteDeCorrection($idPere,$idFils) // Constructeur appelé si l'on souhaite insérer un noeud
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s');
		
		$_id = getAvailableId();
		if($_id == "null") // On n'a plus d'ID disponible (dépassé le nombre maximal d'ID)
		{
			echo "Pas d'ID disponible <br>";
		}
		// Pour répondre à ton commentaire Sylvain : le père aura forcément enregistré qu'il a un nouveau fils car l'ajout se fait soit par la méthode newSon soit par la méthode addSon
		
		$_idPere = $idPere;
		$_niveau = $_idPere->getNiveau() + 1;
		
		$_fils = getUnitById($_idFils);
		$_fils.setPere($this);
		
		$_pere = getUnitById($_idPere);
		$_pere.addSon(this);
		
	}
	__________________________________________________________________________________________
	*/
	function __construct($id,$fromScratch=true) // Génère en fonction du barême associé l'UDC qui correspond pour un élève donné (on lui passe juste l'ID de l'élève)
	{
		$this->_id = $id;
		if($fromScratch)
		{
			$bareme = getUnitById("0");
			if($bareme == null)
			{
				echo "Erreur : Le barême pour cette épreuve n'existe pas !";
				return;
			}
		}
		else 
		{
			$bareme = getUnitById($id);
			if($bareme == null)
			{
				echo "Erreur : le constructeur s'est perdu (tentative d'accès à une partie du barême qui n'existe pas)";
				return;
			}
		}
		foreach($bareme->getIdFils() as $cur)
		{
			array_push($this->_idFils,replaceFirst($cur,$_id));
			new UniteDeCorrection($cur,false);
		}
		
		upload();
	}
	
	
	function __construct1($arrayData) // Récupération dans la BDD 
	{
		date_default_timezone_set(TIMEZONE);
		
		$_idPere = $arrayData['id_father'];
		$_id = $arrayData['id'];
		$_idFils = $arrayData['id_sons'];
		$_niveau = $arrayData['level']; // Les niveaux s'incrémentent à mesure que l'unité de correction est basse dans l'arbre
		$_note = $arrayData['mark']; // La note attribuée suite à la correction -1 si l'unitée n'est pas corrigée
		$_noteMax = $arrayData['max_mark']; // La note maximale que l'on peut obtenir (définie par le barême)
		$_dateModif = $arrayData['date_modif'];// La date, en format DD/MM/YYYY h:m:s, de dernière modification
		$_idCorrecteur = $arrayData['id_corrector'];
	}
	
	// FONCTIONS (outre getters et setters)
	
	public function replaceFirst($string,$idEleve) // Remplace la première chaîne de caractère de l'ID Eleve_Epreuve_Partie... par l'id de l'élève
	{
		$buff = explode('_',$string);
		$buff[0]=$idEleve;
		return implode('_',$buff);
	}
	
	public function getUnitById($targetId) // Récupère une unité de correction par son ID
	{
		$targetObject = null;
		
		$res = $db->prepare("SELECT * FROM units WHERE id = ?");
		$res->execute(array($targetId));
		
		$rep = $res->fetch();
		if($rep) // Si il y a une entrée
		{
			return new UniteDeCorrection($rep);
		}
		else
			echo "Pas d'entrée d'ID ".$targetId." dans la BDD. ";
		
		return null;
	}
	
	public function delInts($string) // Retourn la partie lettrée d'une chaîne de type "abcdef123456" (non sensible à la casse)
	{
		for($i=0;$i<strlen($string);$i++)
		{
			if(in_array($string[$i],array('1','2','3','4','5','6','7','8','9') )) // C'est un nombre
				return intval(substr($string),$i-count($string));
		}
	}
	public function getInts($string)// Retourne les ints d'une chaîne de type "abcdef123456" (non sensible à la casse)
	{
		for($i=0;$i<strlen($string);$i++)
		{
			if(in_array($string[$i],array('1','2','3','4','5','6','7','8','9') ) )// C'est un nombre
				return intval(substr($string),$i);
		}
	}
	
	public function getAvailableId() // DEPRECATED // Algorithme bourrin d'obtention d'un ID non assigné... on les teste tous jusque l'obtention d'un ID valide
	{
		$cur = 0;
		for($cur = 0;$cur < IDMAX;$cur++) // 2147483647 = 2^(31) = la valeur maximale d'un int
		{
			$res = $db->prepare("SELECT id FROM units WHERE id = ?");
			$res->execute(array($cur));
			if(!$res->fetch()) // Aucune entrée où l'ID = cur 
			{
				return $cur;
			}
		}
		return -1;
	}
	
	public function getRootId() // Récupère l'ID du premier des pères
	{
		if($_idPere == -1)
			return $this->_id;
		$pere = getUnitById($_idPere);
		return $pere->getRootId();
	}
	
	public function getRoot()// DEPRECATED  // Récupère le premier des pères
	{
		if($_idPere == -1)
		{
			return $this;
		} 
		else
		{
			return $this->_pere->getRoot();
		}
	}
	
	public function deleteAll()// Détruit cette unité et ses fils sur le serveur
	{
		$res = $db->prepare("DELETE * FROM units WHERE id LIKE ?% ");
		$res->execute(array($this->_id));
	}
	
	public function upload() // Fonction de mise à jour sur le serveur de l'UdC, peut être appelée aussi si l'entrée n'existe pas encore
	{
		$res = $db->prepare("SELECT * FROM units WHERE id = ?");
		$res->execute(array($this->_id));
		
		$rep = $res->fetch();
		
		if(!$rep) // Pas d'entrée
		{
			$req = $db->prepare('INSERT INTO units(id, id_father, id_sons, data, level, mark, max_mark,id_corrector, date_modif) VALUES(:id, :id_pere, :id_sons, :data, :level, :mark, :max_mark, :id_corrector, :date_modif)');
		}
		else // Entrée
		{
			$req = $db->prepare('UPDATE units SET id_father=:id_father, id_sons=:id_sons, data=:data, level=:level, mark=:mark, max_mark=:max_mark,id_corrector=:id_corrector, date_modif=:date_modif WHERE id = :id');
		}
		
		$req->execute(array(
			'id' => $this->_id,
			'id_father' => $this->_idPere,
			'id_sons' => $this->_idFils,
			'data' => $this->_data,
			'level' => $this->_niveau,
			'mark' => $this->_note,
			'max_mark' => $this->_noteMax,
			'id_corrector' => $this->_idCorrecteur,
			'date_modif' => $this->_dateModif
			));
	}
	
	public function updateDate()
	{
		$_dateModif = date('d/m/Y h:i:s');
	}
	
	public function newSon()// DEPRECATED // Créer immédiatement un fils vide
	{
		$NewSon = new UniteDeCorrection($this->_id);
		addSon($NewSon);
	}
	
	public function addSon(&$Son)// DEPRECATED // Ajouter un fils défini au préalable
	{// Le "&" est nécessaire pour passer des équivalents de pointeurs.
		array_push($this->_idFils,$Son->getId());
		array_push($this->_fils,$Son);
		
	}
	
	/*
	public function insert($UdC,$idPere) // Insérer une UdC comme fils d'une autre (son niveau est automatiquement recalculé)
	{
		$Pere = getUnitById($idPere);
		$UdC->setNiveau($Pere->getNiveau()+1);
		$Pere->addSon($UdC);
		
	}*/
	
	public function uploadAll()// DEPRECATED // Uploader l'objet et tous ses fils
	{
		upload();
		foreach($_fils as $cur) // On parcourt tous les fils 
		{
			$cur->upload(); // On recommence sur chacun des fils
		}
	}
	
	// SETTERS
	
	public function setPere(&$pere){$this->_pere = $pere;}
	public function setIdPere($idPere){$this->_idPere = $idPere;}
	public function setId($id){$this->_id = $id;}
	public function setIdFils($idFils){$this->_idFils = $idFils;}
	public function setNiveau($niveau){$this->_niveau = $niveau;}
	public function setNote($note){$this->_note = $note;
	updateDate();
	}
	public function setNoteMax($noteMax){$this->_noteMax = $noteMax;}
	public function setDateModif($dateModif){$this->_dateModif = $dateModif;} // Ne devrait à priori jamais être utilisé
	public function setIdCorrecteur($derCo){$this->_idCorrecteur=$derCo;}
	
	// GETTERS
	public function getPere(){return $this->_pere;}
	public function getIdPere(){return $this->_idPere;}
	public function getId(){return $this->_id;}
	public function getIdFils(){return $this->_idFils;}
	public function getNiveau(){return $this->_niveau;}
	public function getNote(){return $this->_note;}
	public function getNoteMax(){return $this->_noteMax;}
	public function getDateModif(){return $this->_dateModif;}
	public function getIdCorrecteur(){return $this->_idCorrecteur;}

}
?>
