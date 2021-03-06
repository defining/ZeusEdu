<?php

require_once '../../../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();

// définition de la class USER utilisée en variable de SESSION
require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
session_start();

if (!(isset($_SESSION[APPLICATION]))) {
    echo "<script type='text/javascript'>document.location.replace('".BASEDIR."');</script>";
    exit;
}

$User = $_SESSION[APPLICATION];
$acronyme = $User->getAcronyme();

require_once INSTALL_DIR.'/inc/classes/class.Files.php';
$Files = new Files();

$unAn = time() + 365 * 24 * 3600;
$coursGrp = Application::postOrCookie('coursGrp', $unAn);
$idTravail = Application::postOrCookie('idTravail', $unAn);
$matricule = Application::postOrCookie('matricule', $unAn);

// lister tous les travaux, sauf les "archive"
$listeTravaux = $Files->listeTravaux($acronyme, $coursGrp, array('hidden','readwrite', 'readonly','termine'));

require_once INSTALL_DIR.'/smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = '../../templates';
$smarty->compile_dir = '../../templates_c';

$smarty->assign('listeTravauxCours', $listeTravaux);
$smarty->assign('idTravail', $idTravail);
$smarty->assign('coursGrp', $coursGrp);
$smarty->assign('idTravail', $idTravail);

echo $smarty->fetch('casier/listeTravaux.tpl');
