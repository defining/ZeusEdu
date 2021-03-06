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

$unAn = time() + 365 * 24 * 3600;

$start = $_POST['start'];
$end = $_POST['end'];

$coursGrp = isset($_POST['coursGrp']) ? $_POST['coursGrp'] : Null;

$ds = DIRECTORY_SEPARATOR;
$module = $Application->getModule(3);
require_once INSTALL_DIR.$ds.$module.$ds."inc/classes/classJdc.inc.php";
$Jdc = new Jdc();

// Application::afficher(array($start, $end, $coursGrp, $acronyme));
if ($coursGrp == 'synoptique') {
    $eventsList = $Jdc->getSynoptiqueCours($start, $end, $acronyme);
    // Application::afficher($eventsList);
    }
    else $eventsList = $Jdc->getEvents4Cours($start, $end, $coursGrp, $acronyme);

echo json_encode($eventsList);
