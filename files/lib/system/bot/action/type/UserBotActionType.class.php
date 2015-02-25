<?php
namespace wcf\system\bot\action\type;
use wcf\data\bot\action\BotAction;
use wcf\system\bot\action\type\AbstractUserBotActionType;
use wcf\data\user\UserAction;

/**
 * Deletes given users
 *
 * @author		Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license		creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package		com.kawas.wcf.bot
 * @category	Community Framework
 */
class UserBotActionType extends AbstractUserBotActionType{
	/**
	 * avaible groups and user IDs for this groups
	 * @var array<array>
	 */
	public $userIDsToActionName;

	/**
	 * @see	wcf\data\bot\actionType\IBotActionType::init()
 	 */
	public function init() {
		parent::init();
		
		$this->userIDsToActionName = array(
			'ban' => array(),
			'unban' => array(),
			'disable' => array(),
			'enabale' => array(),
			'delete' => array()
		);
	}

	/**
	 * @see	wcf\data\bot\actionType\IBotActionType::execute()
	 */
	public function execute(BotAction $action) {
		parent::execute($action);

		$this->userIDsToActionName[$action->getOption('action_name')][] = $action->getOption('user_id');
	}

	/**
	 * @see	wcf\system\bot\action\type\IBotActionType::shutdownExecute()
	 */
	public function shutdownExecute() {
		parent::shutdownExecute();
		
		foreach ($this->userIDsToActionName as $actionName => $userIDs) {
			$users = array();
			
			foreach($userIDs as $userID){
				$user = $this->getUser($userID);
				if(is_object($user)) $users[] = $user;
			}
			
			if (count($users) != 0) {	
				$objectAction = new UserAction($users, $actionName, array());
				$objectAction->executeAction();
			}
		}
	}
	
}
