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
class DisableUserBotActionType extends AbstractUserBotActionType{

	/**
	 * user IDs
	 * @var array<integer>
	 */
	public $userIDs;
	
	/**
	 * user IDs
	 * @var array<integer>
	 */
	public $actionName = 'disable';
	
	/**
	 * @see	wcf\system\bot\action\type\IBotActionType::init()
 	 */
	public function init() {
		parent::init();
		$this->userIDs = array();
	}

	/**
	 * @see	wcf\system\bot\action\type\IBotActionType::execute()
	 */
	public function execute(BotAction $action) {
		parent::execute($action);
		$this->userIDs[] = $action->getOption('user_id');
	}

	/**
	 * @see	wcf\system\bot\action\type\IBotActionType::shutdownExecute()
	 */
	public function shutdownExecute() {
		parent::shutdownExecute();
		
		$users = array();
		
		foreach($this->userIDs as $userID){
			$user = $this->getUserList()->search($userID);
			if(is_object($user)) $users[] = $user;
		}
		
		if (count($users) != 0) {	
			$objectAction = new UserAction($users, $this->actionName);
			$objectAction->executeAction();
		}
		
	}
}
