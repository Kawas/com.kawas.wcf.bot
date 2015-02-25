<?php
namespace wcf\acp\form;
use wcf\form\AbstractForm;
use wcf\system\exception\UserInputException;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;
use wcf\util\ArrayUtil;
use wcf\util\StringUtil;
use wcf\util\HeaderUtil;
use wcf\system\request\LinkHandler;
use wcf\data\bot\event\BotEventList;
use wcf\data\bot\action\type\BotActionTypeList;
use wcf\data\bot\action\BotActionEditor;
use \wcf\system\bot\Bot;

/**
 * Creates an new bot action
 * 
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotActionAddForm extends AbstractForm {
	/**
	 * @see	wcf\page\AbstractPage::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.user.bot.actionAdd';
	
	/**
	 * @see	wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.general.bot.canManageBot');
	
	
	public $botAction;
	
	public $eventList;
	
	public $eventID = 0;
	
	public $actionTypeList;
	
	public $actionTypeID = 0;
	
	public $useEventParameters = true;
	
	public $actionName;
	
	public $isDisabled = false;
	
	/**
	 * @see	wcf\form\IForm::readFormParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		$this->eventList = new BotEventList();
		$this->eventList->readObjects();
		
		$this->actionTypeList = new BotActionTypeList();
		if (!WCF_BOT_SECURITY_MODE_DISABLED) {
			$this->actionTypeList->getConditionBuilder()->add("isRisky = ?", array(0));
		}
		$this->actionTypeList->readObjects();
	
	}

	/**
	 * @see	wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
	
		if (isset($_POST['actionName'])) $this->actionName = StringUtil::trim($_POST['actionName']);
		if (isset($_POST['eventID'])) $this->eventID = intval($_POST['eventID']);
		if (isset($_POST['useEventParameters'])) $this->useEventParameters = true;
		if (isset($_POST['actionTypeID'])) $this->actionTypeID = intval($_POST['actionTypeID']); 
	}
	
	/**
	 * @see	wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		if (empty($this->actionName)) {
			throw new UserInputException('actionName', 'empty');
		}
		
		$throwException = true;
		foreach ($this->eventList->getObjects() AS $event){
			if($event->eventID == $this->eventID) $throwException = false;
		}
		if ($throwException) {
			throw new UserInputException('eventID', 'empty');
		}
		
		$throwException = true;
		foreach ($this->actionTypeList->getObjects() AS $actionType){
			if($actionType->actionTypeID == $this->actionTypeID) {
				$throwException = false;
				$this->isDisabled = $actionType->isRisky;
			}
		}
		if ($throwException) {
			throw new UserInputException('actionTypeID', 'empty');
		}
	}
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
			

	   
	}
	
	/**
	 * @see	wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
	
		$action = BotActionEditor::create(array( 
			'eventID' => $this->eventID,
			'actionName' => $this->actionName,
			'useEventParameters' => intval($this->useEventParameters),
			'actionTypeID' => $this->actionTypeID,
			'isDisabled' => $this->isDisabled
		));
		
		// show success
		WCF::getTPL()->assign(array(
		  'success' => true
		));
		
		HeaderUtil::redirect(LinkHandler::getInstance()->getLink('BotActionEdit', array('id' => $action->getObjectID())));
		exit;
    
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'action' => 'add',
			'events' => $this->eventList->getObjects(),
			'actionTypes' => $this->actionTypeList->getObjects(),
			'bot' => Bot::getInstance(),
			'actionName' => $this->actionName,
			'eventID' => $this->eventID,
			'actionTypeID' => $this->actionTypeID,
			'useEventParameters' => $this->useEventParameters,
		));
		
		
	}
}
