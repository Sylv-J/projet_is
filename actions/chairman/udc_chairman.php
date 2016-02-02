<?php

// Ceci est un copier coller de Unite_de_correction. Il est ici pour permettre d'utiliser les fonctions, sans restriction de pathfinding vers master_db.php
include_once("../../master_db.php");
$db = masterDB::getDB();

class udc_chairman
{
	// CONSTANTES
	
	const TIMEZONE = "Europe/London"; // Date GMT
	const IDMAX = 2147483646; // 2147483647 = 2^(31) = la taille max d'un int
	
	// ATTRIBUTS
	
	// Uploadés
	private $_idPere = "null";
	private $_data='';
	private $_id = "null";
	private $_idFils = array();
	private $_niveau = 0; // Les niveaux s'incrémentent à mesure que l'unité de correction est basse dans l'arbre
	private $_note = -1; // La note attribuée suite à la correction -1 si l'unitée n'est pas corrigée
	private $_noteMax = 0; // La note maximale que l'on peut obtenir (définie par le barême)
	private $_dateModif = false;// a-t-on corrig鰭odifi顬'UdC ?
	private $_idCorrecteur = "null"; // Ne sera égal à un ID que pour les plus petites udc
	
	// Non uploadés
	private $_pere = NULL;// DEPRECATED // Référence vers le père
	private $_fils = array(NULL);// DEPRECATED // Référence vers les fils
	
	
	
	// CONSTRUCTEURS
	/* DEPRECATED __________________________________________________________________________________________
	
	public function udc_chairman() // DEPRECATED // Constructeur de base de l'unité de correction
	{
		date_default_timezone_set(TIMEZONE);
		$_dateModif = date('d/m/Y h:i:s');
		$_id = getAvailableId(); // interaction avec le serveur
		if($_id == "null") // On n'a plus d'ID disponible (dépassé le nombre maximal d'ID)
		{
			echo "Pas d'ID disponible <br>";
		}
	}
	
	public function udc_chairman($idPere) // Constructeur appelé au moment de la création d'un fils
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

	
	public function udc_chairman($idPere,$idFils) // Constructeur appelé si l'on souhaite insérer un noeud
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
	function __construct()
	{
		date_default_timezone_set(udc_chairman::TIMEZONE);
		
		$this->_idPere = "null";
		$this->_data='';
		$this->_id = "null";
		$this->_idFils = array();
		$this->_niveau = 0; // Les niveaux s'incrémentent à mesure que l'unité de correction est basse dans l'arbre
		$this->_note = -1; // La note attribuée suite à la correction -1 si l'unitée n'est pas corrigée
		$this->_noteMax = 0; // La note maximale que l'on peut obtenir (définie par le barême)
		$this->_dateModif = false;
		$this->_idCorrecteur = "null"; // Ne sera égal à un ID que pour les plus petites udc
		
	}
	
	static function fromId($id,$fromScratch=true) // Génère en fonction du barême associé l'UDC qui correspond pour un élève donné (on lui passe juste l'ID de l'élève)
	{
		$res = new udc_chairman();
		$res->_id = $id;
		if($fromScratch) // Si l'on n'a encore rien crꩠ(ie on est sur la racine)
		{
			$bareme = udc_chairman::getUnitById("0");
			if($bareme == null)
			{
				echo "Erreur : Le bar뮥 pour cette 걲euve n'existe pas ! <br>";
				return;
			}
		}
		else // Sinon, on est sur une branche
		{
			$bareme = udc_chairman::getUnitById($res->getFirstOut($id));
			if($bareme == null)
			{
				echo "Erreur : le constructeur s'est perdu (tentative d'acc鳠ࠠune partie du bar뮥 qui n'existe pas) <br>";
				return;
			}
		}
		foreach($bareme->getIdFils() as $cur) // On parcourt tous les fils de l'UDC du bar뮥 o񠬧on se situe
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
				
				
				udc_chairman::fromId($newIdSon,false);
			}
		}
		
		$res->upload();
		
		return $res;
	}
	
	
	static function fromData($arrayData,$images=false) // Récupération dans la BDD ou depuis un formulaire
	{	
		$res = new udc_chairman();
		
		if(!$images) // Remplissage "࡬a main"
		{
			$res->_idPere = $arrayData['id_father'];
			$res->_idFils = explode(',',$arrayData['id_sons']);
			$res->_niveau = $arrayData['level'];
			$res->_note = $arrayData['mark']; // La note attribuée suite à la correction -1 si l'unitée n'est pas corrigée
			$res->_noteMax = $arrayData['max_mark']; // La note maximale que l'on peut obtenir (définie par le barême)
			$res->_dateModif = $arrayData['date_modif'];
			$res->_idCorrecteur = $arrayData['id_corrector'];
		}
		$res->_id = $arrayData['id'];
		
		if($images)// Remplissage "secr굡ire" // A MODIFIER
		{
			// Partie BDD
			$res = udc_chairman::fromId($arrayData['id']); // On crꥠl'entrꥠcorrespondant ࡬'ꭨve sur la BDD
			
			$res->_idPere = implode('_',array($res->_id,$arrayData['epreuve']));// On rꤵp鳥 l'ID de son p鳥
			
			$temp = udc_chairman::getUnitById($res->_idPere); // On signale au p鳥 qu'il a un nouveau fils !
			$temp->addSon($res->_id); // "
			$temp->upload();// "
			
			
			
			// Partie fichiers
			
			$target_dir = '/'.$arrayData['annee'].'/'.$arrayData['concours'].'/'.$arrayData['filiere'].'/'.$arrayData['id'].'/'.$arrayData['epreuve'].'/';
			
			$to_upload = explode(',',$arrayData['path']);
			foreach($_FILES as $cur)
			{
				$target_file = $cur["name"].basename($cur["name"],'.png'); // On remplace 'path/file_nbr.png' par 'file_nbr'
				$target_file = explode('_',$target_file);// On rꤵp鳥 un array ['file','nbr']
				$target_file = end($target_file);// On garde le dernier ꭩment. Ici, $target_file = 'nbr'
				$target_file = $target_dir.$target_file;// On rajoute le dossier dans lequel il faudra le copier.
				
				if(move_uploaded_file($cur["tmp_name"],$target_file)) // On tente d'upload
				{
					echo "Le fichier ".basename($to_upload)." a bien 굩 copi顳ur le serveur <br>";
				} else {
					echo "Une erreur s'est produite lors de l'upload de ".basename($to_upload)."<br>";
				}
			}
		}
		
		$res->upload();
		return $res;
	} 
	
	public static function getUnitById($targetId) // Récupère une unité de correction par son ID
	{
		global $db;
		$targetObject = null;
	
		$res = $db->prepare("SELECT * FROM units WHERE id = ?");
		$res->execute(array($targetId));
	
		$rep = $res->fetch();
		if($rep) // Si il y a une entrée
		{
			return udc_chairman::fromData($rep);
		}
		else
			echo "Pas d'entrꥠd'ID ".$targetId." dans la BDD. <br>";
	
			return null;
	}
	
	public static function generateBareme($struct) // Gꯨre un bar뮥 ࡰartir d'un texte comme celui donn顤ans "test_bareme.txt"
	{
		$toGen = explode("\n",$struct); // tableau contenant les diff곥nts ppe
		
		// Racine du bar鮥 :
		$udc = new udc_chairman();
		$udc->setId("0");
		$udc->upload();
		
		//$togen = ['Maths1_Partie1_Exercice1_10','Maths1_Partie1_Exercice2_5',...] par ex
		foreach($toGen as $cur)
		{
			$tmp = explode('_',$cur); // $tmp = ['Maths1','Partie1','Exercice1','10'];
			for($i=1;$i<count($tmp)-1;$i++)
			{
				$toCheck = implode('_',array_slice($tmp,1,$i)); // Rꢳsemble les ꭩments (ie premier it곡tion 'Maths1', deuxi鮥 it곡tion 'Maths1_Partie1'...
				// Pour pouvoir tester s'ils existent sur la db, et les upload
				if(null == udc_chairman::getUnitById($toCheck)) // En cas d'existence sur la BDD, rien n'est ࡦaire. En cas de non-existence en revanche, il faut le crꦲ
				{
					$udc = new udc_chairman();
					$udc->setId($toCheck);
					$udc->setNiveau($i);
					
					$idPere = $i == 1 ? "0" : implode('_',array_slice($tmp,1,$i-1));// Pourquoi $i-1 ? Car on est en train de v곩fier si Maths1_Epreuve1_Exercice1 existe par ex. Alors notre p鳥 c'est Maths1_Epreuve1.
					$udc->setIdPere($idPere); // Le  p鳥 est ajout顣omme notre p鳥
							
					$pere = udc_chairman::getUnitById($idPere);
					$pere->addSon($toCheck); // On s'ajoute ࡬a liste des fils de notre p鳥
					$pere->upload();// On upload dans la BDD
					
						
					if($i == count($tmp)-2) // On en est au plus petit ꭩment d'UdC
					{
						$note = array_slice($tmp,$i+1,$i+1);
						$note = intval(reset($note));
						$udc->setNote($note);
						$udc->setNoteMax($note); // Pourquoi $i+1 ? Car ici, si la taille de $tmp est n, $i = n-2. Donc le dernier ꭩment (la note) est $i+1=n-1
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
	
	public function replaceFirst($string,$idEleve) // DEPRECATED // Remplace la première chaîne de caractère de l'ID Eleve_Epreuve_Partie... par l'id de l'élève
	{
		$buff = explode('_',$string);
		$buff[0]=$idEleve;
		return implode('_',$buff);
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
		global $db;
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
		$pere = udc_chairman::getUnitById($_idPere);
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
		global $db;
		$res = $db->prepare("DELETE * FROM units WHERE id LIKE ?% ");
		$res->execute(array($this->_id));
	}
	
	public function upload() // Fonction de mise à jour sur le serveur de l'UdC, peut être appelée aussi si l'entrée n'existe pas encore
	{
		global $db;
		$res = $db->prepare("SELECT * FROM units WHERE id = ?");
		$res->execute(array($this->_id));
		
		$rep = $res->fetch();
		
		if(!$rep) // Pas d'entrée
		{
			$req = $db->prepare('INSERT INTO units(id, id_father, id_sons, data, level, mark, max_mark,id_corrector, date_modif) VALUES(:id, :id_father, :id_sons, :data, :level, :mark, :max_mark, :id_corrector, :date_modif)');
		}
		else // Entrée
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
		$req->bindValue(':id_sons',implode(',',$this->_idFils),PDO::PARAM_STR);
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
	
	public function newSon()// DEPRECATED // Créer immédiatement un fils vide
	{
		$NewSon = new udc_chairman($this->_id);
		addSon($NewSon);
	}
	
	public function addSon($idSon)// Ajouter un fils défini au préalable
	{// Le "&" est nécessaire pour passer des équivalents de pointeurs.
		array_push($this->_idFils,$idSon);
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
	$this->updateDate();
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
