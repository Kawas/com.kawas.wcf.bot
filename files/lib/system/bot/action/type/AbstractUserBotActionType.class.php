<?php
namespace wcf\system\bot\action\type;
use wcf\system\WCF;
use wcf\system\bot\action\type\IBotActionType;
use wcf\data\bot\action\type\BotActionType;
use wcf\data\bot\action\BotAction;
use wcf\data\user\UserList;

/**
 * abstract class for user related action types
 *
 * @author		Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license		creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package		com.kawas.wcf.bot
 * @category	Community Framework
 */
abstract class AbstractUserBotActionType extends BotActionType implements IBotActionType {

	/**
	 * needed user IDs
	 * @var array<integer>
	 */
	static public $neededUserIDs;
	
	/**
	 * action name for UserAction class
	 * @var wcf\data\user\UserList
	 */
	static public $userList;
	
	/**
	 * @see	wcf\system\bot\action\type\IBotActionType::init()
 	 */
	public function init() {
		if(!is_array(self::$neededUserIDs)) self::$neededUserIDs = array();
	}

	/**
	 * @see	wcf\system\bot\action\type\IBotActionType::execute()
	 */
	public function execute(BotAction $action) {
		self::$neededUserIDs[] = $action->getOption('user_id');
	}

	/**
	 * @see	wcf\system\bot\action\type\IBotActionType::shutdownExecute()
	 */
	public function shutdownExecute() {
		if (!is_object(self::$userList)) {
			self::$userList = new UserList();
			self::$userList->setObjectIDs(self::$neededUserIDs);
			self::$userList->readObjects();
		}
	}
	
	/**
	 * returns user list
	 * 
	 * @return wcf\data\user\UserList
	 */
	public function getUserList() {
		return self::$userList;
	}
	
	/**
	 * returns user by id
	 * 
	 * @param	integer	$userID
	 * @return 	wcf\data\user\User
	 */
	public function getUser($userID) {
		return self::$userList->search($userID);
	}
	
}
