<?php
include_once("../master_db.php");
$db = masterDB::getDB();

class UniteDeCorrection
{
	// CONSTANTES
	
	const TIMEZONE = "Europe/London"; // Date GMT
	const IDMAX = 2147483646; // 2147483647 = 2^(31) = la taille max d'un int
	//const SEPARATOR = ";";
	
	// ATTRIBUTS
	
	// UploadÃ©s
	private $_idPere = NULL;
	private $_data='';
	private $_id = NULL;
	private $_idFils = array();
	private $_niveau = 0; // Les niveaux s'incrÃ©mentent Ã  mesure que l'unitÃ© de correction est basse dans l'arbre
	private $_note = -1; // La note attribuÃ©e suite Ã  la correction -1 si l'unitÃ©e n'est pas corrigÃ©e
	private $_noteMax = 0; // La note maximale que l'on peut obtenir (dÃ©finie par le barÃªme)
	private $_dateModif = false;// a-t-on corrigé/modifié l'UdC ?
	private $_idCorrecteur = 0; // Ne sera Ã©gal Ã  un ID que pour les plus petites udc
	
	// Non uploadÃ©s
	private $_pere = NULL;// DEPRECATED // RÃ©fÃ©rence vers le pÃ¨re
	private $_fils = array(NULL);// DEPRECATED // RÃ©fÃ©rence vers les fils
	
	
	
	// CONSTRUCTEURS
	/* DEPRECATED __________________________________________________________________________________________
	
	public function UniteDeCorrection() // DEPRECATED // Constructeur de base de l'unitÃ© de correction
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s');
		$_id = getAvailableId(); // interaction avec le serveur
		if($_id == "null") // On n'a plus d'ID disponible (dÃ©passÃ© le nombre maximal d'ID)
		{
			echo "Pas d'ID disponibleÂ <br>";
		}
	}
	
	public function UniteDeCorrection($idPere) // Constructeur appelÃ© au moment de la crÃ©ation d'un fils
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s');
		
		$_id = getAvailableId(); // interaction avec le serveur
		if($_id == "null") // On n'a plus d'ID disponible (dÃ©passÃ© le nombre maximal d'ID)
		{
			echo "Pas d'ID disponibleÂ <br>";
		}
		
		$_idPere = $idPere;
		$_niveau = $_idPere->getNiveau() + 1;
		
		$_pere = getUnitById($_idPere);
		$_pere.addSon($this);
		
	}

	
	public function UniteDeCorrection($idPere,$idFils) // Constructeur appelÃ© si l'on souhaite insÃ©rer un noeud
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s');
		
		$_id = getAvailableId();
		if($_id == "null") // On n'a plus d'ID disponible (dÃ©passÃ© le nombre maximal d'ID)
		{
			echo "Pas d'ID disponibleÂ <br>";
		}
		// Pour rÃ©pondre Ã  ton commentaire Sylvain : le pÃ¨re aura forcÃ©ment enregistrÃ© qu'il a un nouveau fils car l'ajout se fait soit par la mÃ©thode newSon soit par la mÃ©thode addSon
		
		$_idPere = $idPere;
		$_niveau = $_idPere->getNiveau() + 1;
		
		$_fils = getUnitById($_idFils);
		$_fils.setPere($this);
		
		$_pere = getUnitById($_idPere);
		$_pere.addSon(this);
		
	}
	__________________________________________________________________________________________
	*/
	function __construct()
	{
		date_default_timezone_set(UniteDeCorrection::TIMEZONE);
		
		$this->_idPere = NULL;
		$this->_data='';
		$this->_id = NULL;
		$this->_idFils = array();
		$this->_niveau = 0; // Les niveaux s'incrÃ©mentent Ã  mesure que l'unitÃ© de correction est basse dans l'arbre
		$this->_note = -1; // La note attribuÃ©e suite Ã  la correction -1 si l'unitÃ©e n'est pas corrigÃ©e
		$this->_noteMax = 0; // La note maximale que l'on peut obtenir (dÃ©finie par le barÃªme)
		$this->_dateModif = false;
		$this->_idCorrecteur = NULL; // Ne sera Ã©gal Ã  un ID que pour les plus petites udc
		
	}
	
	static function fromId($id,$fromScratch=true) // GÃ©nÃ¨re en fonction du barÃªme associÃ© l'UDC qui correspond pour un Ã©lÃ¨ve donnÃ© (on lui passe juste l'ID de l'Ã©lÃ¨ve)
	{
		$res = new UniteDeCorrection();
		$res->_id = $id;
		if($fromScratch) // Si l'on n'a encore rien créé (ie on est sur la racine)
		{
			$bareme = UniteDeCorrection::getUnitById("0");
			if($bareme == null)
			{
				echo "Erreur : Le barême pour cette épreuve n'existe pas ! <br>";
				return;
			}
		}
		else // Sinon, on est sur une branche
		{
			$bareme = UniteDeCorrection::getUnitById($res->getFirstOut($id));
			if($bareme == null)
			{
				echo "Erreur : le constructeur s'est perdu (tentative d'accès à  une partie du barême qui n'existe pas) <br>";
				return;
			}
		}
		foreach($bareme->getIdFils() as $cur) // On parcourt tous les fils de l'UDC du barême où l'on se situe
		{
			$res->setNiveau($bareme->getNiveau());
			$res->setNoteMax(max(array($bareme->getNote(),$bareme->getNoteMax())));
			
			$idPere = $res->getLastOut($res->getId());
			if($idPere != '')
				$res->setIdPere($idPere);
			
			if($cur != null && $cur != '')
			{
				$newIdSon = $res->getFirst($id).'_'.$cur;
			
				array_push($res->_idFils,$newIdSon);
				
				
				UniteDeCorrection::fromId($newIdSon,false);
			}
		}
		
		$res->upload();
		
		return $res;
	}
	
	
	static function fromData($arrayData,$images=false,$publish = true) // RÃ©cupÃ©ration dans la BDD ou depuis un formulaire
	{	
		$res = new UniteDeCorrection();
		$sep = ";";
		
		if(!$images) // Remplissage "à la main"
		{
			$res->_idPere = $arrayData['id_father'];
			$res->_idFils = explode($sep,$arrayData['id_sons']);
			$res->_niveau = $arrayData['level'];
			$res->_note = $arrayData['mark']; // La note attribuÃ©e suite Ã  la correction -1 si l'unitÃ©e n'est pas corrigÃ©e
			$res->_noteMax = $arrayData['max_mark']; // La note maximale que l'on peut obtenir (dÃ©finie par le barÃªme)
			$res->_dateModif = $arrayData['date_modif'];
			$res->_idCorrecteur = $arrayData['id_corrector'];
		}
		$res->_id = $arrayData['id'];
		
		if($images)// Remplissage "secrétaire" // A MODIFIER
		{
			// Partie BDD
			$res = UniteDeCorrection::fromId($arrayData['id']); // On crée l'entrée correspondant à l'élève sur la BDD
			
			/*$idPere = explode('_',$res->_id);
			$idPere = array_slice(0,count($idPere)-1);
			$idPere = implode('_',$idPere);
			
			$res->_idPere = $idPere;// On récupère l'ID de son père
			
			$temp = UniteDeCorrection::getUnitById($res->_idPere); // On signale au père qu'il a un nouveau fils !
			$temp->addSon($res->_id); // "
			$temp->upload();// "
			*/
			
			
			// Partie fichiers
			
			$target_dir = '/'.$arrayData['anneeconcoursfiliere'].'/'.$arrayData['id'].'/'.$arrayData['epreuve'].'/';
			
			foreach($_FILES as $cur)
			{
				$target_file = $cur["name"].basename($cur["name"],'.png'); // On remplace 'path/file_nbr.png' par 'file_nbr'
				$target_file = explode('_',$target_file);// On récupère un array ['file','nbr']
				$target_file = end($target_file);// On garde le dernier élément. Ici, $target_file = 'nbr'
				$target_file = $target_dir.$target_file;// On rajoute le dossier dans lequel il faudra le copier.
				
				if(move_uploaded_file($cur["tmp_name"],$target_file)) // On tente d'upload
				{
					echo "Le fichier ".basename($target_file)." a bien été copié sur le serveur <br>";
				} else {
					echo "Une erreur s'est produite lors de l'upload de ".basename($target_file)."<br>";
				}
			}
		}
		
		$publish ? $res->upload() : '';
		return $res;
	} 
	
	public static function getUnitById($targetId) // RÃ©cupÃ¨re une unitÃ© de correction par son ID
	{
		global $db;
		$targetObject = null;
	
		$res = $db->prepare("SELECT * FROM units WHERE id = ?");
		$res->execute(array($targetId));
	
		$rep = $res->fetch();
		if($rep) // Si il y a une entrÃ©e
		{
			return UniteDeCorrection::fromData($rep);
		}
		else
			echo "Pas d'entrée d'ID ".$targetId." dans la BDD. <br>";
	
			return null;
	}
	
	public static function generateBareme($struct) // Génère un barême à partir d'un texte comme celui donné dans "test_bareme.txt"
	{
		$toGen = explode("\n",$struct); // tableau contenant les différents ppe
		
		// Racine du barème :
		$udc = new UniteDeCorrection();
		$udc->setId("0");
		$udc->upload();
		
		//$togen = ['Maths1_Partie1_Exercice1_10','Maths1_Partie1_Exercice2_5',...] par ex
		foreach($toGen as $cur)
		{
			$tmp = explode('_',$cur); // $tmp = ['Maths1','Partie1','Exercice1','10'];
			for($i=1;$i<count($tmp)-1;$i++)
			{
				$toCheck = implode('_',array_slice($tmp,1,$i)); // Réassemble les éléments (ie premier itération 'Maths1', deuxième itération 'Maths1_Partie1'...
				// Pour pouvoir tester s'ils existent sur la db, et les upload
				if(null == UniteDeCorrection::getUnitById($toCheck)) // En cas d'existence sur la BDD, rien n'est à faire. En cas de non-existence en revanche, il faut le créer
				{
					$udc = new UniteDeCorrection();
					$udc->setId($toCheck);
					$udc->setNiveau($i);
					
					$idPere = $i == 1 ? "0" : implode('_',array_slice($tmp,1,$i-1));// Pourquoi $i-1 ? Car on est en train de vérifier si Maths1_Epreuve1_Exercice1 existe par ex. Alors notre père c'est Maths1_Epreuve1.
					$udc->setIdPere($idPere); // Le  père est ajouté comme notre père
							
					$pere = UniteDeCorrection::getUnitById($idPere);
					$pere->addSon($toCheck); // On s'ajoute à la liste des fils de notre père
					$pere->upload();// On upload dans la BDD
					
						
					if($i == count($tmp)-2) // On en est au plus petit élément d'UdC
					{
						$note = array_slice($tmp,$i+1,$i+1);
						$note = intval(reset($note));
						$udc->setNote($note);
						$udc->setNoteMax($note); // Pourquoi $i+1 ? Car ici, si la taille de $tmp est n, $i = n-2. Donc le dernier élément (la note) est $i+1=n-1
					}
					$udc->upload();// On upload dans la BDD
					
				}
			}
		}
		unset($pere);
		unset($udc);
	}
	
	// FONCTIONS (outre getters et setters)
	
	public function getFirstOut($string)
	{
		$buff = explode('_',$string);
		$buff = array_slice($buff,1,count($buff));
		$buff = implode('_',$buff);
		
		return $buff;
	}
	
	public function getFirst($string)
	{
		$buff = explode('_',$string);
		
		return $buff[0];
	}
	
	public function getLastOut($string)
	{
		$buff = explode('_',$string);
		$buff = array_slice($buff,0,count($buff)-1);
		$buff = implode('_',$buff);
		
		return $buff;
	}
	
	public function replaceFirst($string,$idEleve) // DEPRECATED // Remplace la premiÃ¨re chaÃ®ne de caractÃ¨re de l'ID Eleve_Epreuve_Partie... par l'id de l'Ã©lÃ¨ve
	{
		$buff = explode('_',$string);
		$buff[0]=$idEleve;
		return implode('_',$buff);
	}
	
	public static function getAllSmallest($id = '') // Récupère toutes les unités de correction qu'il faut donc assigner (ie utile pour le chairman par ex)
	{
		$allSmallest = array();
		
		global $db;
		if($id == '')
		{
			$res = $db->prepare("SELECT * FROM units WHERE (id_sons ='' OR id_sons is null)");  // On récupère les ppe
		}
		else
		{
			$res = $db->prepare("SELECT * FROM units WHERE (id_sons ='' OR id_sons is null) AND (id REGEXP :id_#)");
			$res->bindValue(":id",$id,PDO::PARAM_STR);
		}
		
		$res->execute();
		$rep = $res->fetch();
		
		while($rep != null && $rep != false)
		{
			$buff = UniteDeCorrection::fromData($rep,false,false);
			if($buff->getRootId() != '0') // Pas un barême
			{
				array_push($allSmallest,$buff);
			}
			
			$rep = $res->fetch();
		}
		
		return $allSmallest;
	}
	
	public function getImage() // Récupère l'image correspondant à l'unité. Si elle n'existe pas, on notifie l'utilisateur par une erreur.
	{
		
	}
	
	public function delInts($string) // Retourn la partie lettrÃ©e d'une chaÃ®ne de type "abcdef123456" (non sensible Ã  la casse)
	{
		for($i=0;$i<strlen($string);$i++)
		{
			if(in_array($string[$i],array('1','2','3','4','5','6','7','8','9') )) // C'est un nombre
				return intval(substr($string),$i-count($string));
		}
	}
	public function getInts($string)// Retourne les ints d'une chaÃ®ne de type "abcdef123456" (non sensible Ã  la casse)
	{
		for($i=0;$i<strlen($string);$i++)
		{
			if(in_array($string[$i],array('1','2','3','4','5','6','7','8','9') ) )// C'est un nombre
				return intval(substr($string),$i);
		}
	}
	
	public function getAvailableId() // DEPRECATED // Algorithme bourrin d'obtention d'un ID non assignÃ©... on les teste tous jusque l'obtention d'un ID valide
	{
		global $db;
		$cur = 0;
		for($cur = 0;$cur < IDMAX;$cur++) // 2147483647 = 2^(31) = la valeur maximale d'un int
		{
			$res = $db->prepare("SELECT id FROM units WHERE id = ?");
			$res->execute(array($cur));
			if(!$res->fetch()) // Aucune entrÃ©e oÃ¹ l'ID = cur 
			{
				return $cur;
			}
		}
		return -1;
	}
	
	public function getRootId() // RÃ©cupÃ¨re l'ID du premier des pÃ¨res
	{
		if($this->_idPere == NULL)
			return $this->_id;
		$pere = UniteDeCorrection::getUnitById($this->_idPere);
		return $pere->getRootId();
	}
	
	public function getRoot()// DEPRECATED  // RÃ©cupÃ¨re le premier des pÃ¨res
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
	
	public function deleteAll()// DÃ©truit cette unitÃ© et ses fils sur le serveur
	{
		global $db;
		$res = $db->prepare("DELETE * FROM units WHERE id LIKE ?% ");
		$res->execute(array($this->_id));
	}
	
	public function upload() // Fonction de mise Ã  jour sur le serveur de l'UdC, peut Ãªtre appelÃ©e aussi si l'entrÃ©e n'existe pas encore
	{
		global $db;
		$sep = ";";
		$res = $db->prepare("SELECT * FROM units WHERE id = ?");
		$res->execute(array($this->_id));
		
		$rep = $res->fetch();
		
		if(!$rep) // Pas d'entrÃ©e
		{
			$req = $db->prepare('INSERT INTO units(id, id_father, id_sons, data, level, mark, max_mark,id_corrector, date_modif) VALUES(:id, :id_father, :id_sons, :data, :level, :mark, :max_mark, :id_corrector, :date_modif)');
		}
		else // EntrÃ©e
		{
			$req = $db->prepare('UPDATE units SET id_father=:id_father, id_sons=:id_sons, data=:data, level=:level, mark=:mark, max_mark=:max_mark,id_corrector=:id_corrector, date_modif=:date_modif WHERE id = :id');
		}
		
		if(count($this->_idFils)>=2)
		{
			for($i = 0;$i<count($this->_idFils);$i++)
			{
				if($this->_idFils[$i] == '')
				{
					unset($this->_idFils[$i]);
				}
			}
			$idFils = array_values($this->_idFils);
		}
		
		$req->bindValue(':id',$this->_id,PDO::PARAM_STR);
		$req->bindValue(':id_father',$this->_idPere,PDO::PARAM_STR);
		$req->bindValue(':id_sons',implode($sep,$this->_idFils),PDO::PARAM_STR);
		$req->bindValue(':data',$this->_data,PDO::PARAM_STR);
		$req->bindValue(':level',$this->_niveau,PDO::PARAM_INT);
		$req->bindValue(':mark',$this->_note,PDO::PARAM_INT);
		$req->bindValue(':max_mark',$this->_noteMax,PDO::PARAM_INT);
		$req->bindValue(':id_corrector',$this->_idCorrecteur,PDO::PARAM_STR);
		if($this->_dateModif || !$rep)
			$req->bindValue(':date_modif',date('Y-m-d H:i:s'),PDO::PARAM_STR);
		else
			$req->bindValue(':date_modif',$rep['date_modif'],PDO::PARAM_STR);
		
		$req->execute();
	}
	
	public function updateDate()
	{
		$_dateModif = true;
	}
	
	public function newSon()// DEPRECATED // CrÃ©er immÃ©diatement un fils vide
	{
		$NewSon = new UniteDeCorrection($this->_id);
		addSon($NewSon);
	}
	
	public function addSon($idSon)// Ajouter un fils dÃ©fini au prÃ©alable
	{// Le "&" est nÃ©cessaire pour passer des Ã©quivalents de pointeurs.
		array_push($this->_idFils,$idSon);
	}
	
	/*
	public function insert($UdC,$idPere) // InsÃ©rer une UdC comme fils d'une autre (son niveau est automatiquement recalculÃ©)
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
	$this->updateDate();
	}
	public function setNoteMax($noteMax){$this->_noteMax = $noteMax;}
	public function setDateModif($dateModif){$this->_dateModif = $dateModif;} // Ne devrait Ã  priori jamais Ãªtre utilisÃ©
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
