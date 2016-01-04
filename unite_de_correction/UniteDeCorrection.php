<?php
include_once("../master_db.php");
$db = masterDB::getDB();

class UniteDeCorrection
{
	// CONSTANTES
	
	const TIMEZONE = "Europe/London"; // Date GMT
	const IDMAX = 2147483646; // 2147483647 = 2^(31) = la taille max d'un int
	
	// ATTRIBUTS
	
	private $_idPere = -1;
	private $_data='';
	private $_id = -1;
	private $_idFils = array();
	private $_niveau = 0; // Les niveaux s'incrémentent à mesure que l'unité de correction est basse dans l'arbre
	private $_note = -1; // La note attribuée suite à la correction -1 si l'unitée n'est pas corrigée
	private $_noteMax = 0; // La note maximale que l'on peut obtenir (définie par le barême)
	private $_dateModif = "";// La date, en format DD/MM/YYYY h:m:s, de dernière modification
	private $_idCorrecteur = -1; // Ne sera égal à un ID que pour les plus petites udc
	
	// CONSTRUCTEURS
	
	public function UniteDeCorrection() // Constructeur de base de l'unité de correction
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s');
		$_id = getAvailableId(); // interaction avec le serveur
		if($_id == -1) // On n'a plus d'ID disponible (dépassé le nombre maximal d'ID)
		{
			echo "Pas d'ID disponible <br>";
		}
	}
	
	public function UniteDeCorrection($idPere) // Constructeur appelé au moment de la création d'un fils
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s');
		
		$_id = getAvailableId(); // interaction avec le serveur
		if($_id == -1) // On n'a plus d'ID disponible (dépassé le nombre maximal d'ID)
		{
			echo "Pas d'ID disponible <br>";
		}
		
		$_idPere = $idPere;
		$_niveau = $_idPere->getNiveau() + 1;
		
	}

	
	public function UniteDeCorrection($idPere,$idFils) // Constructeur appelé si l'on souhaite insérer un noeud
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s');
		
		$_id = getAvailableId();
		if($_id == -1) // On n'a plus d'ID disponible (dépassé le nombre maximal d'ID)
		{
			echo "Pas d'ID disponible <br>";
		}
		// Pour répondre à ton commentaire Sylvain : le père aura forcément enregistré qu'il a un nouveau fils car l'ajout se fait soit par la méthode newSon soit par la méthode addSon
		
		$_idPere = $idPere;
		$_niveau = $_idPere->getNiveau() + 1;
		
	}
	
	public function UniteDeCorrection($arrayData) // Constructeur appelé lors d'un getUnitById
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
	
	public function getAvailableId() // Algorithme bourrin d'obtention d'un ID non assigné... on les teste tous jusque l'obtention d'un ID valide
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
	
	public function getRoot() // Récupère l'ID du premier des pères
	{
		if($_idPere == -1)
		{
			return $this->_id;
		} 
		else
		{
			return getUnitById($_idPere)->getRoot();
		}
	}
	
	public function deleteAll() // Détruit cette unité et ses fils, et fait de même sur le serveur
	{
		$cur = null;
		
		while(!empty($this->_idFils))
		{
			$cur = pop($this->_idFils);
			$cur->deleteAll();
		}
		
		$res = $db->prepare("DELETE * FROM units WHERE id = ? ");
		$res->execute(array($this->_id));
	}
	
	public function upload() // Fonction de mise à jour sur le serveur de l'UdC, peut être appelée aussi si l'entrée n'existe pas encore
	{
		$res = $db->prepare("SELECT * FROM units WHERE id = ?");
		$res->execute(array($targetId));
		
		$rep = $res->fetch();
		
		if(!$rep) // Pas d'entrée
		{
			$req = $bdd->prepare('INSERT INTO units(id, id_father, id_sons, data, level, mark, max_mark,id_corrector, date_modif) VALUES(:id, :id_pere, :id_sons, :data, :level, :mark, :max_mark, :id_corrector, :date_modif)');
		}
		else // Entrée
		{
			$req = $bdd->prepare('UPDATE units SET id_father=:id_father, id_sons=:id_sons, data=:data, level=:level, mark=:mark, max_mark=:max_mark,id_corrector=:id_corrector, date_modif=:date_modif WHERE id = :id');
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
	
	public function newSon() // Créer immédiatement un fils vide
	{
		$NewSon = new UniteDeCorrection($this->_id);
		addSon($NewSon);
	}
	
	public function addSon($Son)// Ajouter un fils défini au préalable
	{
		array_push(&($this->_idFils),$Son->getId());
		
	}
	
	
	public function insert($UdC,$idPere) // Insérer une UdC comme fils d'une autre (son niveau est automatiquement recalculé)
	
	{
		$Pere = getUnitById($idPere);
		$UdC->setNiveau($Pere->getNiveau()+1);
		$Pere->addSon($UdC);
		
	}
	
	public function uploadAll() // Uploader l'objet et tous ses fils
	{
		upload();
		foreach($_idFils as $cur) // On parcourt tous les fils 
		{
			getUnitById($cur)->upload(); // On recommence sur chacun des fils
		}
	}
	
	// SETTERS
	
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
