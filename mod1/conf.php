<?php

	// DO NOT REMOVE OR CHANGE THESE 2 LINES:
$MCONF['name'] = 'user_txswitchbeuserM1';
$MCONF['script'] = '_DISPATCH';

if($GLOBALS['BE_USER']->user['ses_backuserid']){
	$MCONF['access'] = 'admin';
}else{
	$MCONF['access'] = 'user,group';
}

$MLANG['default']['tabs_images']['tab'] = 'moduleicon.gif';
$MLANG['default']['ll_ref'] = 'LLL:EXT:switch_beuser/mod1/locallang_mod.xml';
?>