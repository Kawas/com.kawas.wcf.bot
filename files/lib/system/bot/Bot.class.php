<?php
namespace wcf\system\bot;
use wcf\system\WCF;
use wcf\system\SingletonFactory;
use wcf\system\cache\builder\BotCacheBuilder;
use wcf\data\user\User;

/**
 * Represents the community bot
 * 
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class Bot extends SingletonFactory{

	/**
	 * actions
	 * @var	array<\wcf\data\bot\action\BotAction>
	 */
	public $actionList;

	/**
	 * actions types
	 * @var	array<\wcf\data\bot\action\type\BotActionType>
	 */
	public $actionTypeList;

	/**
	 * initialized actions types
	 * @var	array<boolean>
	 */
	public $initActionTypes;

	/**
	 * status of the bot
	 * @var	boolean
	 */
	protected $isEnabled = WCF_MODULE_BOT;

	/**
	 * install date of community bot
	 * @var	integer
	 */
	public $installDate = WCF_BOT_INSTALL_DATE;

	/**
	 * options of the actions
	 * @var	array<array>
	 */
	public $options;
	
	/**
	 * user object of bot
	 * @var	wcf\data\user\User
	 */
	public $userObj;
	
	/**
	 * user object of current user for data restore
	 * @var	wcf\data\user\User
	 */
	public $currentUserObj;
	
	/**
	 * @see	\wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		$this->userObj = new User(WCF_BOT_USERID);

		if($this->userID == 0) $this->isEnabled = false;

		if($this->isEnabled()){
			$this->currentUserObj = WCF::getUser();
			
			$cache = BotCacheBuilder::getInstance();
			$this->actionList = $cache->getData(array(), 'actionList');
			$parameters = array(
				'page_title' => PAGE_TITLE,
				'bot_user_id' => $this->userID,
				'bot_username' => $this->username
			);
			foreach($this->actionList->objects as $action) {
				$action->registerEventParameters($parameters);
			}

			$this->actionTypeList = $cache->getData(array(), 'actionTypeList');
			$objects = $this->actionTypeList->objects;
			$this->actionTypeList->objects = array();
			foreach($objects as $object) {
				$this->actionTypeList->objects[$object->actionTypeName] = new $object->className(null, null, $object);
			}

			$this->options = $cache->getData(array(), 'options');
			$this->initActionTypes = array();
		}
	}

	/**
	 * Returns vars of user object
	 * 
	 * @param	string		$varName
	 * @return	mixed
	 */
	public function __get($varName) {
		return $this->userObj->__get($varName);
	}

	/**
	 * manipulates user data for action classes with user specific user content
	 */
	public function manipulateUser() {
		WCF::getUser()->__construct(null, null, $this->userObj);
	}
	
	/**
	 * Restores user data 
	 */
	public function restoreUser() {
		WCF::getUser()->__construct(null, null, $this->currentUserObj);
	}

	/**
	 * Returns true if bot is enabled.
	 * 
	 * @param	string		$eventName
	 * @return	boolean
	 */
	public function isEnabled($eventName = null) {
		if (!$this->isEnabled) return false;
		if ($eventName !== null AND count($this->getActions($eventName) == 0)) return false;

		return true;
	}


	/**
	 * Initialize action type.
	 * 
	 * @param	string		$actionTypeName
	 */
	public function initActionType($actionTypeName) {
		if(!isset($this->initActionTypes[$actionTypeName])){ 
			$this->initActionTypes[$actionTypeName] = true;
			$this->actionTypeList->objects[$actionTypeName]->botObj = $this;
			$this->actionTypeList->objects[$actionTypeName]->init();
		}
	}

	/**
	 * Executes all actions with the given eventname.
	 * 
	 * @param	array		$eventName
	 * @param	array		$parameters
	 */
	public function fireActions($eventName, array $parameters = null) {
		$this->manipulateUser();
		
		foreach($this->actionList->objects as $action) {
			if($action->eventName == $eventName AND !$action->isDisabled) {
				$this->initActionType($action->actionTypeName);
				if($parameters !== null) $action->registerEventParameters($parameters);
				$action->execute(false);
			}
		}
		
		$this->restoreUser();
	}

	/**
	 * Returns all actions with the given eventname.
	 * 
	 * @param	string		$actionTypeName
	 * @return 	array<\wcf\data\bot\action\BotAction>
	 */
	public function getActions($eventName) {
		$actions = array();

		foreach($this->actionList->objects as $action) {
			if($action->eventName == $eventName AND !$action->isDisabled AND (!$action->isRisky OR WCF_BOT_SECURITY_MODE_DISABLED)) {
				$this->initActionType($action->actionTypeName);
				$actions[] = $action;
			}
		}

		return $actions;
	}

	/**
	 * Execute shutdown methode of all initialized actions.
	 */
	public function __destruct(){
		if($this->isEnabled()) {
			$this->manipulateUser();

			foreach($this->actionTypeList->objects AS $actionTypeName => $action) {
				if (isset($this->initActionTypes[$actionTypeName]) AND !$action->isDisabled){
					$action->shutdownExecute();
				}
			}
			
			$this->restoreUser();
		}
	}

}
?>
