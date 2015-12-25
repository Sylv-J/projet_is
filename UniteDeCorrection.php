class UniteDeCorrection
{
	// CONSTANTES
	
	const TIMEZONE = "Europe/London"; // Date GMT
	
	// ATTRIBUTS
	
	private $_idPere = -1;
	private $_id = -1;
	private $_idFils = array();
	private $_niveau = 0; // Les niveaux s'incrémentent à mesure que l'unité de correction est basse dans l'arbre
	private $_note = 0; // La note attribuée suite à la correction
	private $_noteMax = 0; // La note maximale que l'on peut obtenir (définie par le barême)
	private $_dateModif = "";// La date, en format DD/MM/YYYY h:m:s, de dernière modification
	
	// CONSTRUCTEURS
	
	public function UniteDeCorrection() // Constructeur de base de l'unité de correction
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s');
		//$_id = $server->getAvailableId() // interaction avec le serveur
	}
	
	public function UniteDeCorrection($idPere) // Constructeur appelé au moment de la création d'un fils
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s'');
		
		//$_id = $server->getAvailableId() // interaction avec le serveur
		
		$_idPere = $idPere;
		$_niveau = $_idPere->getNiveau() + 1;
	}
	
	public function UniteDeCorrection($idPere,$idFils) // Constructeur appelé si l'on souhaite insérer un noeud
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s'');
		
		//$_id = $server->getAvailableId() // interaction avec le serveur
		
		$_idPere = $idPere;
		$_niveau = $_idPere->getNiveau() + 1;
		
		
	}
	
	// FONCTIONS (outre getters et setters)
	
	public function updateDate()
	{
		$_dateModif = date('d/m/Y h:i:s'');
	}
	
	public function newSon() // Créer immédiatement un fils vide
	{
		$NewSon = new UniteDeCorrection($this->_id);
		addSon($NewSon);
		
		$updateDate();
	}
	
	public function addSon($Son)// Ajouter un fils défini au préalable
	{
		array_push(&($this->_idFils),$Son->getId());
		
		updateDate();
	}
	
	
	public function insert($UdC,$idPere) // Insérer une UdC comme fils d'une autre (son niveau est automatiquement recalculé)
	{
		//$Pere = $server->getUdcById($idPere);
		//$UdC->setNiveau($Pere->getNiveau()+1);
		//$Pere->
		
		updateDate();
	}
	
	public function upload() // Uploader les données locales sur le serveur
	{
		//$server->upload($this); // On upload les données concernant cet objet... J'avoue ne pas savoir quel format ça prendra
		foreach($_idFils as $cur) // On parcourt tous les fils 
		{
			//$server->getUdcById($cur)->upload(); // On recommence sur chacun des fils
		}
	}
	
	// SETTERS
	
	public function setIdPere($idPere){$this->_idPere = $idPere;}
	public function setId($id){$this->_id = $id;}
	public function setIdFils($idFils){$this->_idFils = $idFils;}
	public function setNiveau($niveau){$this->_niveau = $niveau;}
	public function setNote($note){$this->_note = $note;}
	public function setNoteMax($noteMax){$this->_noteMax = $noteMax;}
	public function setDateModif($dateModif){$this->_dateModif = $dateModif;} // Ne devrait à priori jamais être utilisé
	
	// GETTERS
	
	public function getIdPere(){return $this->_idPere;}
	public function getId(){return $this->_id;}
	public function getIdFils(){return $this->_idFils;}
	public function getNiveau(){return $this->_niveau;}
	public function getNote(){return $this->_note;}
	public function getNoteMax(){return $this->_noteMax;}
	public function getDateModif(){return $this->_dateModif;}
}