<?php

require_once '../config.inc.php';
include INSTALL_DIR.'/inc/entetes.inc.php';

// ----------------------------------------------------------------------------
//

$smarty->assign('action', $action);
$smarty->assign('mode', $mode);

$acronyme = $user->acronyme();
$smarty->assign('acronyme', $acronyme);

$etape = isset($_POST['etape']) ? $_POST['etape'] : null;

$classe = isset($_POST['classe']) ? $_POST['classe'] : null;
$smarty->assign('listeClasses', $Ecole->listeGroupes());
$id = isset($_POST['id']) ? $_POST['id'] : null;
$matricule = isset($_POST['matricule']) ? $_POST['matricule'] : null;
$smarty->assign('matricule', $matricule);

$onglet = isset($_POST['onglet']) ? $_POST['onglet'] : 0;
$smarty->assign('onglet', $onglet);

$noBulletin = isset($_POST['noBulletin']) ? $_POST['noBulletin'] : PERIODEENCOURS;

require_once INSTALL_DIR."/$module/inc/classes/classAthena.inc.php";

switch ($action) {
    case 'ficheEleve':
        if ($matricule != null) {
            require 'inc/ficheEleve.inc.php';
        }
        break;

    case 'modifier':
           require 'inc/modifier.inc.php';
        break;

    case 'supprimer':
        require 'inc/supprimer.inc.php';
        break;

    case 'enregistrer':
        require 'inc/enregistrer.inc.php';
        break;

    case 'synthese':
        require 'inc/synthese.inc.php';
        break;

    }

// si rien n'a encore été assigné au sélecteur, on présente le sélecteur par défaut.
if ($smarty->getTemplateVars('corpsPage') == null) {
    $smarty->assign('classe', $classe);
    $listeEleves = ($classe != null) ? $Ecole->listeEleves($classe, 'groupe') : null;
    $smarty->assign('action', 'ficheEleve');
    $smarty->assign('mode', 'wtf');
    $elevesSuivis = Athena::getEleveUser($acronyme);
    $smarty->assign('elevesSuivis', $elevesSuivis);
    $clients = Athena::clientCoaching();
    $smarty->assign('clients', $clients);

    $smarty->assign('corpsPage', 'elevesSuivis');
    $smarty->assign('listeEleves', $listeEleves);
    $smarty->assign('selecteur', 'selectClasseEleve');
}

//
// ----------------------------------------------------------------------------
$smarty->assign('executionTime', round($chrono->stop(), 6));
$smarty->display('index.tpl');
