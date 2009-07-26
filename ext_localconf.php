<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

#require_once(t3lib_extMgm::extPath('beuser').'class.tx_beuser_switchbackuser.php');

#$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['logoff_pre_processing'][] = 'tx_beuser_switchbackuser->switchBack';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass'][] = 'EXT:switch_beuser/class.tx_changetca.php:tx_changetca';
?>
