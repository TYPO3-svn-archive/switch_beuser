<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE == 'BE') {
	t3lib_extMgm::addModulePath('user_txswitchbeuserM1', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
		
	t3lib_extMgm::addModule('user', 'txswitchbeuserM1', '', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
}

// example to override locallang values of this Module
// so you can use your own error or email messages
//
// write this line to typo3conf/extTables.php or in any other ext_tables.php of an extensions 
// and clear BE Cache
//$TYPO3_CONF_VARS['BE']['XLLfile']['EXT:switch_beuser/mod1/locallang.xml']='EXT:switch_beuser/res/override_locallang.xml'; 
?>