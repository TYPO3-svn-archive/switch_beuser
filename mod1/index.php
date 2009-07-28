<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Sven Juergens <post@t3area.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */


$LANG->includeLLFile('EXT:switch_beuser/mod1/locallang.xml');
require_once(PATH_t3lib . 'class.t3lib_scbase.php');
$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]



/**
 * Module 'Switch BeUser' for the 'switch_beuser' extension.
 *
 * @author	Sven Juergens <post@t3area.de>
 * @package	TYPO3
 * @subpackage	tx_switchbeuser
 */
class  tx_switchbeuser_module1 extends t3lib_SCbase {
				var $pageinfo;
				var $config =array();

				/**
				 * Initializes the Module
				 * @return	void
				 */
				function init()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;
					
					$confTemp = '';
					$confTemp = $GLOBALS['BE_USER']->getTSConfig('tx_switch_beuser');
					$this->config = $confTemp['properties'];
					unset($confTemp);
					if(t3lib_div::_GP('SwitchUser')){
						$this->switchUser(t3lib_div::_GP('SwitchUser'));
					}

					parent::init();

				}


				/**
				 * Main function of the module. Write the content to $this->content
				 * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
				 *
				 * @return	[type]		...
				 */
				function main()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

				
						// initialize doc
		if (t3lib_div::int_from_ver(TYPO3_version) >= t3lib_div::int_from_ver('4.2.0')) {					
					$this->doc = t3lib_div::makeInstance('template');
					$this->doc->setModuleTemplate(t3lib_extMgm::extPath('switch_beuser') . 'mod1//mod_template.html');
						
		}else{				
                    $this->doc = t3lib_div::makeInstance('mediumDoc');
		}			
					
					$this->doc->backPath = $BACK_PATH;
					
					$this->content='';

					$this->content.=$this->doc->header($GLOBALS['LANG']->getLL('moduleheader'));
					$this->content.=$this->doc->spacer(5);

							// Draw the form
						$this->doc->form = '<form action="" method="post" enctype="multipart/form-data">';

							// JavaScript
						$this->doc->JScode = '
							<script language="javascript" type="text/javascript">
								script_ended = 0;
								function jumpToUrl(URL)	{
									document.location = URL;
								}
							</script>
						';
						$this->doc->postCode='
							<script language="javascript" type="text/javascript">
								script_ended = 1;
								if (top.fsMod) top.fsMod.recentIds["web"] = 0;
							</script>
						';
							// Render content:
						$this->moduleContent();

	if (t3lib_div::int_from_ver(TYPO3_version) >= t3lib_div::int_from_ver('4.2.0')) {
						// compile document
					$markers['CONTENT'] = $this->content;

							// Build the <body> for the module
					$this->content = $this->doc->startPage($LANG->getLL('title'));
					$this->content.= $this->doc->moduleBody($this->pageinfo, $docHeaderButtons, $markers);
					$this->content.= $this->doc->endPage();
					$this->content = $this->doc->insertStylesAndJS($this->content);
	}else{			
				    $this->content.=$this->doc->startPage($LANG->getLL('title'));
                    $this->content.=$this->doc->spacer(5);
					$this->content.= $this->doc->endPage();					
	}				
				
				}

				/**
				 * Prints out the module HTML
				 *
				 * @return	void
				 */
				function printContent()	{

					$this->content.=$this->doc->endPage();
					echo $this->content;
				}

				/**
				 * Generates the module content
				 *
				 * @return	void
				 */
				function moduleContent()	{
						$this->content .= $this->getUserList();
				}
				
				
				function getUserList() {
					//globales content
					$content = '';
					
					$select_fields = '*';
					$from_table = 'be_users'; 	// 	string 	See exec_SELECTquery()
					$where_clause = 'admin=0' . t3lib_befunc::deleteClause($from_table) . t3lib_befunc::BEenableFields($from_table); 
		
					$groupBy='' ;				//string 	See exec_SELECTquery()
					$orderBy=''; 				//string 	See exec_SELECTquery()
					$limit='' ;					//string 	See exec_SELECTquery()
					
					$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
								$select_fields,
								$from_table,
								$where_clause,
								$groupBy,
								$orderBy,
								$limit
							);

				$allCells['USERS'] = '<table border="0" cellspacing="0" cellpadding="0" width="100%"><td><b>' . $GLOBALS['LANG']->getLL('usernames'). '</b></td><td width="12"></td></tr></table>';
				$allGroups[]=$allCells;			
									
				$curUid = $GLOBALS['BE_USER']->user['uid'];
				$uListArr=array();
				$i = 0;	
				foreach ($rows as $uDat) {
					if($this->userDisallowed($uDat['uid']) || $this->userGroupDisallowed($uDat['usergroup'])){
						$uItem ='';
					}else{
						$uItem = '<tr>
									<td width="230" height="20px" class="'.($i % 2 == 0 ? 'bgColor4' : 'bgColor6').'">' .
								 		t3lib_iconWorks::getIconImage('be_users',$uDat,$GLOBALS['BACK_PATH'],'align="top" title="'.$uDat['username'].'"') .
							 	 		htmlspecialchars($uDat['username']) .
							 	 	'</td><td nowrap="nowrap" height="20px" class="'.($i % 2 == 0 ? 'bgColor4' : 'bgColor6').'">';
							 	 
						if ($curUid != $uDat['uid'] && !$uDat['disable'] && ($uDat['starttime'] == 0 || $uDat['starttime'] < time()) && ($uDat['endtime'] == 0 || $uDat['endtime'] > time()))	{
								$uItem .= '<a href="'.t3lib_div::linkThisScript(array('SwitchUser'=>$uDat['uid'])).'" target="_top"><img '.t3lib_iconWorks::skinImg($GLOBALS['BACK_PATH'],'gfx/su_back.gif').' border="0" align="top" title="'.htmlspecialchars('Switch user to: '.$uDat['username']).'" alt="" /></a>';
						}
						$uItem .= '</td></tr>';
						$uListArr[] = $uItem;
					$i += 1;	
					}

				}
				$allCells['USERS'] = '<table border="0" cellspacing="0" cellpadding="0" width="100%">'.implode('',$uListArr).'</table>';
				
				
				$allGroups[]=$allCells;


				// Make table
			$outTable='';
			$TDparams=' nowrap="nowrap" class="bgColor5" valign="top"';
			$i = 0;
			foreach ($allGroups as $allCells) {
				
				$outTable.='<tr><td'.$TDparams.'>'.implode('</td><td'.$TDparams.'>',$allCells).'</td></tr>';
				$TDparams=' nowrap="nowrap" class="'.($i++ % 2 == 0 ? 'bgColor4' : 'bgColor6').'" valign="top"';
			}
			$outTable='<table border="0" cellpadding="2" cellspacing="2">'.$outTable.'</table>';
			$content.= $this->doc->spacer(10);
			$content.= $this->doc->section('Result',$outTable,0,1);
		
			return $content;
				}

	function userDisallowed($uid){
		$check ='';
		
		if(!isset($this->config['disallowedUsers']) || empty($this->config['disallowedUsers'])){
			$check = false;
		}elseif(t3lib_div::inList($this->config['disallowedUsers'],$uid)){
			$check = true;
		}
		else{
			$check = false;
		}
		return $check;
	}

	function userGroupDisallowed($usergroup = ''){
		
		$check = '';
		$groups =array();
		
		if($usergroup =='' || !isset($this->config['disallowedGroups']) || empty($this->config['disallowedGroups'])){
			$check = false;
		}
		else{
			$groups = t3lib_div::intExplode(',',$this->config['disallowedGroups'],1);
			
			foreach($groups as $group){
				if(t3lib_div::inList($usergroup,$group)){
					$check = true;
					break;
				}
			}
			
			
		}
		
		return $check;
	}
				
				/**
	 * Switches to a given user (SU-mode) and then redirects to the start page of the backend to refresh the navigation etc.
	 *
	 * @param	array		BE-user record that will be switched to
	 * @return	void
	 */
	function switchUser($switchUser)	{
		$check = false;
		
		$uRec=t3lib_BEfunc::getRecord('be_users',$switchUser);

		if($this->userDisallowed($uRec['uid']) || $this->userGroupDisallowed($uRec['usergroup']) || $uRec['admin'] == 1){
			$check = true;
			$this->switchNotAllowed($uRec['username']);
		}
		
		
		//if (is_array($uRec) && $GLOBALS['BE_USER']->isAdmin())	{
		if (is_array($uRec) && $uRec['admin'] == 0 && $check == false)	{
	
			$updateData['ses_userid'] = $uRec['uid'];
			$updateData['ses_backuserid'] = intval($GLOBALS['BE_USER']->user['uid']);

			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('be_sessions', 'ses_id='.$GLOBALS['TYPO3_DB']->fullQuoteStr($GLOBALS['BE_USER']->id, 'be_sessions').' AND ses_name=\'be_typo_user\' AND ses_userid='.intval($GLOBALS['BE_USER']->user['uid']),$updateData);
	
			header('Location: '.t3lib_div::locationHeaderUrl($GLOBALS['BACK_PATH'].'index.php'.($GLOBALS['TYPO3_CONF_VARS']['BE']['interfaces']?'':'?commandLI=1')));
			exit;
		}
	}
	
	function switchNotAllowed($username){

		$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['switch_beuser']);
		$sysWarningEmail = $GLOBALS['TYPO3_CONF_VARS']['BE']['warning_email_addr'];
		
		if($sysWarningEmail || $extConf['warning_email']){
			$email = $extConf['warning_email']?$extConf['warning_email']:$sysWarningEmail;
			$subject = $GLOBALS['LANG']->getLL('misbehavior_subject');
			$message = $GLOBALS['LANG']->getLL('misbehavior_text') . chr(10) . chr(10);
			$message .= $GLOBALS['LANG']->getLL('username') . htmlspecialchars($GLOBALS['BE_USER']->user['username']) . chr(10);
			$message .= $GLOBALS['LANG']->getLL('trytoswitch') . htmlspecialchars($username) . chr(10);
			$message .= 'IP: ' . t3lib_div::getIndpEnv('REMOTE_ADDR') . chr(10);
			$message .= $GLOBALS['LANG']->getLL('time') . strftime($GLOBALS['LANG']->getLL('timeformat'),time());
			t3lib_div::plainMailEncoded($email,$subject,$message);
			
			$GLOBALS['BE_USER']->simplelog($message, 'switch_beuser', 2);
			
		}
		$this->content .='<span style="color:red;font-size:12px;">' .  $GLOBALS['LANG']->getLL('email_sended_message') . '</span>';
		
	}
	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/switch_beuser/mod1/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/switch_beuser/mod1/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_switchbeuser_module1');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>