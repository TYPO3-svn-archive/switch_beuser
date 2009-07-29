<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass'][] = 'EXT:switch_beuser/class.tx_switchbeuser_hooks.php:tx_switchbeuser_hooks';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:switch_beuser/class.tx_switchbeuser_hooks.php:tx_switchbeuser_hooks';

?>
