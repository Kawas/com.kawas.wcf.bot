<?php
namespace wcf\acp\page;
use wcf\page\SortablePage;
use \wcf\system\bot\Bot;
use \wcf\system\WCF;

/**
 * shows all bot actions
 * 
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotActionListPage extends SortablePage {
	/**
	 * @see	wcf\page\AbstractPage::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.user.bot.actionList';
	
	/**
	 * @see	wcf\page\SortablePage::$defaultSortField
	 */
	public $defaultSortField = 'actionID';
	
	/**
	 * @see	wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.general.bot.canManageBot');
	
	/**
	 * @see	wcf\page\MultipleLinkPage::$objectListClassName
	 */
	public $objectListClassName = 'wcf\data\bot\action\BotActionList';
	
	/**
	 * @see	wcf\page\AbstractPage::$templateName
	 */
	public $templateName = 'botActionList';
	
	/**
	 * @see	wcf\page\SortablePage::$validSortFields
	 */
	public $validSortFields = array('actionID', 'actionName', 'eventName', 'actionTypeName');
	
	
	/**
	 * @see	\wcf\page\MultipleLinkPage::readData()
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
	}

	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
	
		WCF::getTPL()->assign(array(
			'bot' => Bot::getInstance()
		));
	
	
	}
	

}
