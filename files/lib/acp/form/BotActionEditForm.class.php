<?php
namespace wcf\acp\form;
use wcf\acp\form\AbstractOptionListForm;
use wcf\system\exception\UserInputException;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;
use wcf\util\ArrayUtil;
use wcf\util\StringUtil;
use wcf\data\bot\action\BotAction;
use wcf\data\bot\event\BotEvent;
use wcf\data\bot\action\BotActionEditor;
use wcf\data\bot\action\BotActionAction;
use \wcf\system\bot\Bot;

/**
 * 
 * 
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotActionEditForm extends AbstractOptionListForm {
	/**
	 * @see	wcf\page\AbstractPage::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.user.bot.actionList';
	
	/**
	 * @see	wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.general.bot.canManageBot');
	
	/**
	 * @see	wcf\page\AbstractPage::$templateName
	 */
	public $templateName = 'botActionEdit';
	
	/**
	 * @see	wcf\acp\form\AbstractOptionListForm::$optionHandlerClassName
	 */
	public $optionHandlerClassName = 'wcf\system\bot\BotOptionHandler';
	
	public $action;
	public $event;
	
	public $actionID = 0;
	public $actionName;
	
	/**
	 * @see	wcf\acp\form\AbstractOptionListForm::$languageItemPattern
	 */
	protected $languageItemPattern = 'wcf.acp.bot.option\d+';
	
	/**
	 * @see	wcf\acp\form\AbstractOptionListForm::$categoryName
	 */
	public $categoryName = 'wcf.acp.bot';
	
	/**
	 * @see	wcf\form\IForm::readFormParameters()
	 */
	public function readParameters() {
		if (isset($_REQUEST['id'])) $this->actionID = intval($_REQUEST['id']);
		
		 parent::readParameters();
	}
	
	/**
	 * @see	wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		if (empty($this->actionName)) {
			throw new UserInputException('actionName', 'empty');
		}		
		/*
		$optionNames = array('subject', 'message');
		$errorType = array();
		foreach ($this->optionHandler->getOptions() AS $option) {
			$option = $option['object'];
			if (in_array($option->optionName, $optionNames) AND empty($option->optionValue)) {
				$errorType[$option->optionName] = 'empty';
			}
		}
		WCF::getTPL()->assign(array(
			'errorType' => $errorType,
		));
		if(count($errorType) != 0) throw new UserInputException('actionName', 'empty');*/
	}
	
	/**
	 * @see	wcf\acp\form\AbstractOptionListForm::initOptionHandler()
	 */
	protected function initOptionHandler() {
		$this->action  = new BotAction($this->actionID);
		$this->event = new BotEvent($this->action->eventID);
		$this->optionHandler->init($this->action, $this->event);
	}

	/**
	 * @see	wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		if (isset($_POST['actionName'])) $this->actionName = StringUtil::trim($_POST['actionName']);

	}
	
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		$this->actionName = $this->action->actionName;
		parent::readData();
	}
	
	/**
	 * @see	wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();

		$this->objectAction = new BotActionAction(array($this->action->getObjectID()), 'update', array('data' => array(
		  'actionName' => $this->actionName
		)));
		
		$this->objectAction->executeAction();
		
		$options = $this->optionHandler->save('wcf.acp.bot', 'wcf.acp.bot.option');
		
		BotActionEditor::updateOptions($options);
		
		// show success
		WCF::getTPL()->assign(array(
		  'success' => true
		));
		
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'action' => 'edit',
      		'botAction' => $this->action,
      		'actionName' => $this->actionName,
      		'options' => $this->optionHandler->getOptions(),
     		 'langPrefix' => 'wcf.acp.bot.action.option.',
     		 'bot' => Bot::getInstance()
		));
		
		
	}
}
