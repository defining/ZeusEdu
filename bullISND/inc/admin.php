<?php
$niveau = isset($_POST['niveau'])?$_POST['niveau']:Null;
$niveauEleves = isset($_POST['niveauEleves'])?$_POST['niveauEleves']:Null;
$coursGrp = isset($_POST['coursGrp'])?$_POST['coursGrp']:Null;
$cours = isset($_POST['cours'])?$_POST['cours']:Null;
$bulletin = isset($_POST['bulletin'])?$_POST['bulletin']:PERIODEENCOURS;
$classe = isset($_POST['classe'])?$_POST['classe']:Null;
$matricule = isset($_POST['matricule'])?$_POST['matricule']:Null;
$acronyme = isset($_POST['acronyme'])?$_POST['acronyme']:Null;
$verrouiller = isset($_POST['verrouiller'])?$_POST['verrouiller']:0;
$etape = isset($_POST['etape'])?$_POST['etape']:Null;

switch ($mode) {
	case 'ajoutTV':
		// $Bulletin->ajouteTV();

		break;
	
	case 'remplacants':
		$profsParCours = $Ecole->listeCoursGrpProf($Ecole->listeNiveaux());
		$listeRemplacements = $Ecole->listeRemplacants($profsParCours);
		$listeProfs = $Ecole->listeProfs();
		$smarty->assign("listeRemplacements", $listeRemplacements);
		$smarty->assign("listeProfs",$listeProfs);
		$smarty->assign("listeCoursEleves", $Ecole->listeCours($Ecole->listeNiveaux()));
		$smarty->assign("corpsPage", "remplacants");
		break;
	
	case 'attributionsProfs':
		if ($niveau) {
			$listeCoursGrp = $Ecole->listeCoursGrp($niveau);
			$smarty->assign('listeCoursGrp', $listeCoursGrp);
			}
		if ($coursGrp) {
			$smarty->assign('coursGrp',$coursGrp);
			$listeEleves = $Ecole->listeElevesCours($coursGrp);
			$smarty->assign('listeEleves', $listeEleves);
			$listeTousProfs = $Ecole->listeProfs();			
			$smarty->assign('listeTousProfs', $listeTousProfs);
			}

		$etape = isset($_REQUEST['etape'])?$_REQUEST['etape']:Null;
		switch ($etape) {
			case 'addProfs':
				$addProf = isset($_POST['addProf'])?$_POST['addProf']:Null;
				$nbInsert = $Ecole->ajouterProfsCoursGrp($addProf, $coursGrp);
				$smarty->assign("message", array(
									'title'=>"Enregistrement",
									'texte'=>"$nbInsert modification(s")
									);
				break;
			case 'supprProfs':
				$supprProf = isset($_POST['supprProf'])?$_POST['supprProf']:Null;
				$nbSuppressions = $Ecole->supprimerProfsCoursGrp($supprProf, $coursGrp);
				$smarty->assign("message", array(
									'title'=>"Enregistrement",
									'texte'=>"$nbSuppressions suppression(s)")
									);
				break;
			}
			
		$smarty->assign('action',$action);
		$smarty->assign('mode',$mode);
		$smarty->assign('listeNiveaux', $Ecole->listeNiveaux());
		$smarty->assign('niveau',$niveau);
		$listeProfsTitulaires = $Ecole->listeProfsCoursGrp($coursGrp);
		$smarty->assign("listeProfsTitulaires", $listeProfsTitulaires);
		$smarty->assign('selecteur', 'selectNiveauCoursGrp');
		$smarty->assign("corpsPage", "showProfsCours");
		break; 
	
	case 'attributionsEleves':
		if ($etape == 'enregistrer') {
			$listeElevesAdd = isset($_POST['listeElevesAdd'])?array_flip($_POST['listeElevesAdd']):Null;
			$listeElevesDel = isset($_POST['listeElevesDel'])?array_flip($_POST['listeElevesDel']):Null;
			$listeCoursGrp = array($coursGrp=>$coursGrp);
			$nb = 0;
			if ((isset($listeElevesAdd) || isset($listeElevesDel)) && isset($coursGrp) && isset($bulletin)) {
				if (isset($listeElevesAdd)) 
					$nb += $Ecole->addListeElevesListeCoursGrp($listeElevesAdd, $listeCoursGrp, $bulletin);
				if (isset($listeElevesDel))
					$nb += $Ecole->delListeElevesListeCoursGrp($listeElevesDel, $listeCoursGrp, $bulletin);
				$smarty->assign("message", array(
								'title'=>"Enregistrement",
								'texte'=>"$nb enregistrement(s) effectué(s)"));
				}
			}

		$smarty->assign("mode",$mode);
		$smarty->assign("action",$action);
		$smarty->assign("cours", $cours);
		$smarty->assign("coursGrp", $coursGrp);
		$smarty->assign("bulletin", $bulletin);
		if (!(isset($niveau)) && (isset($cours)))
			$niveau = substr($cours,0,1);
		$smarty->assign("niveau", $niveau);
		$listeNiveaux = $Ecole->listeNiveaux();
		$smarty->assign("listeNiveaux", $listeNiveaux);
		$smarty->assign("listePeriodes", $Bulletin->listePeriodes(NBPERIODES));
		$smarty->assign("listeCours", $Ecole->listeCours($listeNiveaux));
		
		$listeCoursGrp = isset($cours)?$Ecole->listeCoursGrpDeCours($cours):Null;

		$smarty->assign("listeCoursGrp", $listeCoursGrp);
		
		$profs = isset($coursGrp)?$Ecole->listeProfsCoursGrp($coursGrp):Null;
		$smarty->assign('profs',$profs);
		
		$listeElevesDel = isset($coursGrp)?$Ecole->listeElevesCours($coursGrp, 'alpha'):Null;
		$smarty->assign('listeElevesDel', $listeElevesDel);

		$listeElevesAdd = ($niveau != '')?$Ecole->listeElevesNiveaux($niveau):Null;

		$smarty->assign('listeElevesAdd', $listeElevesAdd);

		$smarty->assign("selecteur", "selectMatieres");
		$smarty->assign("corpsPage", "showAttributionsEleves");
		break;
	
	case 'programmeEleve':
		$smarty->assign("listeClasses", $Ecole->listeClasses());
		$smarty->assign("listePeriodes", $Bulletin->listePeriodes(NBPERIODES));

		$smarty->assign("bulletin", $bulletin);
		$smarty->assign("action", $action);
		$smarty->assign("mode", $mode);
		$smarty->assign("classe", $classe);
		$smarty->assign("selecteur", "selectClasseEleve");
		if (isset($classe)) {
			$listeEleves = $Ecole->listeEleves($classe,'groupe');
			$smarty->assign("listeElevesClasse", $listeEleves);
			}
		if (isset($matricule)) {
			$eleve = new Eleve($matricule);
			$smarty->assign("eleve", $eleve->getDetailsEleve());
			$smarty->assign("matricule", $matricule);
			$etape = isset($_REQUEST['etape'])?$_REQUEST['etape']:Null;
			switch ($etape) {
				case 'supprimer':
					// recherche des coursGrp à supprimer pour l'élève actuel
					$listeCoursGrp = isset($_POST['listeCoursGrp'])?$_POST['listeCoursGrp']:Null;;

					if (isset($listeCoursGrp)) {
						// produire une liste dont les clefs sont les noms des cours plutôt qu'une suite de numéros sans signification
						$listeCoursGrp = array_fill_keys($listeCoursGrp, Null);
						// la fonction delListeElevesListeCoursGrp attend un tableau indexé sur le matricule; on le lui fournit gentiment avec un seul matricule pour clef, le contenu n'a pas d'importance
						$listeEleves = array($matricule=>'wtf');
						
						// à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier 
						// à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier
						$nb = $Ecole->delListeElevesListeCoursGrp($listeEleves, $listeCoursGrp, $bulletin);
						//$nb = $Ecole->supprimerElevesCoursGrp($listeElevesSuppr, $listeCoursGrp, $bulletin);  // la fonction ci-dessus est plus générale
						// à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier
						// à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier à vérifier 
						
						
						$smarty->assign('etape','');
						$smarty->assign("message", array(
							'title'=>"Enregistrement",
							'texte'=>"$nb modification(s) enregistrée(s)"));
						}
					break;
				case 'ajouter': 
					if (isset($coursGrp)) {
						$listeEleves = array($matricule=>$matricule);
						$listeCoursGrp = array($coursGrp=>$coursGrp);
						// $nb = $Ecole->ajouteElevesCoursGrp($matricule, $coursGrp, $bulletin);
						$nb = $Ecole->addListeElevesListeCoursGrp($listeEleves, $listeCoursGrp, $bulletin);
						}
						$smarty->assign("message", array(
							'title'=>"Enregistrement",
							'texte'=>"$nb modification(s) enregistrée(s)"));
					break;
				default:
					// wtf;
					break;
				}
			$historiqueCours = $Ecole->historiqueCoursEleve($matricule);
			$smarty->assign('historiqueCours', $historiqueCours);
			$listeCoursGrp = $Bulletin->listeCoursGrpEleves($matricule, $bulletin);
			$listeCoursGrp = isset($listeCoursGrp[$matricule])?$listeCoursGrp[$matricule]:Null;;
			$smarty->assign('listeCoursGrp', $listeCoursGrp);

			$listeTousCours = $Ecole->listeCoursGrp($Ecole->listeNiveaux());
			$listeStruct = array();
			foreach ($listeTousCours as $coursGrp=>$details) {
				$cours = $details['cours'];
				$listeStruct[$cours][] = $details;
				}
			$smarty->assign("listeTousCours", $listeStruct);
			$smarty->assign("corpsPage", "programmeEleve");
			}
		break;

	case 'poserVerrous':
		$etape = isset($_REQUEST['etape'])?$_REQUEST['etape']:Null;
		switch ($etape) {
			case 'enregistrer':
				$nb = $Bulletin->saveLocksAdmin($_POST, $bulletin);
				$smarty->assign("message", array(
							'title'=>"Verrouillage des bulletins n0 $bulletin",
							'texte'=>"$nb verrous posés ou supprimés")
							);
				//pas de break;
			case 'voir':
				$smarty->assign("etape", "voir");
				$listeClasses = $Bulletin->listeStructClasses();
				$smarty->assign("listeClasses",$listeClasses);
				$smarty->assign("corpsPage","poserVerrous");
				break;
			default:
				$smarty->assign("etape", "voir");
				break;
			}

		$verrou = isset($_POST['verrou'])?$_POST['verrou']:Null;
		$smarty->assign("bulletin", $bulletin);		
		$smarty->assign("verrou", $verrou);
		$smarty->assign("action", $action);
		$smarty->assign("mode", $mode);
		$smarty->assign("nbBulletins", NBPERIODES);
		$smarty->assign("selecteur", "selectBulletin");
		break;
	case 'verrouClasseCoursEleve':
		$etape = isset($_REQUEST['etape'])?$_REQUEST['etape']:Null;
		switch ($etape) {
			case 'enregistrer':
				$nb = $Bulletin->saveLocksClasseCoursEleve($_POST);
				$stxt = ($verrouiller ==1)?"posés":"supprimés";
				$smarty->assign("message", array(
							'title'=>"Verrouillage des bulletins n0 $bulletin",
							'texte'=>"$nb verrous $stxt")
							);
				// break;
			case 'showNiveau':
				// liste des verrous à inverser 
				$listeVerrous = $Bulletin->listeLocksPeriode($bulletin, $niveau, !$verrouiller);
				$listeVerrousIndetermines = $Bulletin->listeUndefinedLocks($niveau, $listeVerrous);

				$listeProfs = $Ecole->listeCoursGrpProf($niveau);
				$smarty->assign("bulletin",$bulletin);
				$smarty->assign("niveau",$niveau);
				$smarty->assign("verrouiller", $verrouiller);
				$smarty->assign("listeVerrous", $listeVerrous);
				$smarty->assign("listeProfs", $listeProfs);
				$smarty->assign("corpsPage","verrousOuverts");
				break;
			default:
				// wtf
				break;
			}
		$smarty->assign("nbBulletins", NBPERIODES);
		$smarty->assign("listeNiveaux", $Ecole->listeNiveaux());
		$smarty->assign("niveau", $niveau);
		$smarty->assign("bulletin", $bulletin);
		$smarty->assign('action',$action);
		$smarty->assign('mode',$mode);
		$smarty->assign("selecteur","selectBulletinNiveauVerrou");
		break;
	
	case 'rapportCompetences':
		$date = isset($_REQUEST['date'])?$_REQUEST['date']:Null;
		$signature = isset($_REQUEST['signature'])?$_REQUEST['signature']:Null;
		$classe = isset($_REQUEST['classe'])?$_REQUEST['classe']:Null;
		$etape = isset($_REQUEST['etape'])?$_REQUEST['etape']:Null;
		 switch ($etape) {
			 case 'print':
				 if ($classe) {
					$listeEleves = $Ecole->listeEleves('classe',$classe);
					$listeCours = $Ecole->listeCoursClasse($classe);
					$listeCompetences = $Bulletin->listeCompetencesListeCours($listeCours);
					$sommeCotes = $Bulletin->sommeToutesCotes($listeEleves,$listeCours,$listeCompetences);
					$listeAcquis = $Bulletin->listeAcquis($sommeCotes);

					$smarty->assign("classe",$classe);
					$smarty->assign("listeEleves",$listeEleves);
					$smarty->assign("listeCours",$listeCours);
					$smarty->assign("listeCompetences",$listeCompetences);
					$smarty->assign("listeAcquis",$listeAcquis);
				 }
				 // pas de break;
			 default: 
				$listeClasses = $Ecole->listeClasses(array('G','TT'));
				$smarty->assign("listeClasses", $listeClasses);
				$smarty->assign("classe", $classe);
				$smarty->assign("date",$date);
				$smarty->assign("signature",$signature);
				$smarty->assign("DIRECTION", DIRECTION);
				$smarty->assign("selecteur","selectClasseDateSignature");
				$smarty->assign("corpsPage","rapportCompetences");
				break;
			 }
		break;

	case 'competences':
		$listeNiveaux = $Ecole->listeNiveaux();
		$smarty->assign("listeNiveaux", $listeNiveaux);
		if ($niveau) {
			$smarty->assign("niveau", $niveau);
			$listeCoursComp = $Bulletin->listeCoursNiveaux($niveau);
			$smarty->assign("listeCoursComp", $listeCoursComp);
		}
		$etape = isset($_REQUEST['etape'])?$_REQUEST['etape']:Null;
		if ($etape == 'enregistrer') {
			$nbResultats = $Bulletin->enregistrerCompetences($_POST);
			$smarty->assign("message", array(
						'title'=>"Enregistrement",
						'texte'=>"$nbResultats compétence(s) modifiée(s)"));
			}
		$smarty->assign('action',$action);
		$smarty->assign('mode',$mode);
		$smarty->assign("cours",$cours);
		$listeCompetences = $Bulletin->listeCompetencesListeCours($cours);
		$smarty->assign("listeCompetences", $listeCompetences);
		$smarty->assign("corpsPage", "adminCompetences");
		$smarty->assign("selecteur", "selectNiveauCours");
		break;

	case 'situations':
		$smarty->assign("action",$action);
		$smarty->assign("mode",$mode);
		$smarty->assign("etape","showCotes");
		$smarty->assign("nbBulletins", NBPERIODES);
		$smarty->assign("bulletin",$bulletin);
		$smarty->assign("listeClasses", $Ecole->listeClasses());
		// sélecteur incluant la période "0" pour les élèves de 2e
		$smarty->assign("selecteur", "selectBulletin0Classe");
		$etape = isset($_REQUEST['etape'])?$_REQUEST['etape']:Null;

		switch ($etape) {
			case 'enregistrer':
				if (isset($classe)) {
					$nb = $Bulletin->enregistrerSituationsClasse($_POST);
					// recalcul des cotes de situation du bulletin suivant
					$listeSitActuelles = $Bulletin->recalculSituationsClasse($bulletin+1, $classe);
					$nb2 = $Bulletin->enregistrerSituationsRecalc($listeSitActuelles, $bulletin+1);
					$smarty->assign("message", array(
						'title'=>"Modification des situations",
						'texte'=>"$nb situations enregistrées")
						);
					$smarty->assign('etape','showCotes');
				}
				// pas de break;
			case 'showCotes': 
				if ($classe) {
					$listeEleves = $Ecole->listeEleves($classe,'groupe');
					$listeSituations = $Bulletin->getSituations($bulletin, $listeEleves);
					$listeCoursClasse = $Ecole->listeCoursClasse($classe);
					$listeCoursEleves = $Bulletin->listeCoursEleves($listeEleves);

					$smarty->assign("etape","enregistrer");
					$smarty->assign("classe",$classe);
					$smarty->assign("listeSituations", $listeSituations);
					$smarty->assign("listeCoursClasse", $listeCoursClasse);
					$smarty->assign("listeCoursEleves", $listeCoursEleves);
					$smarty->assign("listeEleves", $listeEleves);
					$smarty->assign("corpsPage","grilleSituations");
					}
				break;
			default:
				// wtf;
				break;
		}
		
		break;
	case 'alias':
		$etape = isset($_REQUEST['etape'])?$_REQUEST['etape']:Null;
		switch ($etape) {
			case 'enregistrer':
				if ($acronyme == Null) die("missing user");
				$qui = $Application->changeUserAdmin($acronyme);
				if ($qui == '') die('unknown user');
				$texte = "Vous avez pris l'alias <strong>$qui</strong>";
				$smarty->assign("message", array(
					'title'=>"Alias",
					'texte'=>$texte)
					);
				$smarty->assign ("redirection","index.php");
				$smarty->assign ("time",1000);
				$smarty->assign ('corpsPage', 'redirect');
				break;
			default:
				$listeProfs = $Ecole->listeProfs();
				$smarty->assign("listeProfs", $listeProfs);
				$smarty->assign('selecteur', 'selectProf');
				$smarty->assign("action", $action);
				$smarty->assign("mode", $mode);
				$smarty->assign("etape", 'enregistrer');
				break;
			}
		break;
	
	default: "missing mode";
		break;
	}

?>