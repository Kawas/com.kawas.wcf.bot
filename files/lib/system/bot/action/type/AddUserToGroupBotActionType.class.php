<?php
namespace wcf\system\bot\action\type;
use wcf\system\WCF;
use wcf\system\bot\action\type\IBotActionType;
use wcf\data\bot\action\type\BotActionType;
use wcf\data\bot\action\BotAction;
use wcf\data\user\group\UserGroup;
use wcf\data\user\UserAction;

/**
 * Adds given users to given user group
 *
 * @author		Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license		creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package		com.kawas.wcf.bot
 * @category	Community Framework
 */
class AddUserToGroupBotActionType extends AbstractUserBotActionType{

	/**
	 * avaible groups and user IDs for this groups
	 * @var array<array>
	 */
	public $userIDsToGroupID;
	
	/**
	 * @see	wcf\data\bot\actionType\IBotActionType::init()
 	 */
	public function init() {
		parent::init();
		
		$groups = UserGroup::getGroupsByType();
		foreach ($groups AS $group) {
			// everyone-, guest-, registrated-, admin- group is excluded when security mode is enabled
			if (!WCF_BOT_SECURITY_MODE_DISABLED AND in_array($groupID, array(1, 2, 3, 4))) continue;
			
			$this->userIDsToGroupID[$group->groupID] = array();
		}
	}

	/**
	 * @see	wcf\data\bot\actionType\IBotActionType::execute()
	 */
	public function execute(BotAction $action) {
		parent::execute($action);
		
		$groupID = $action->getOption('group_id');
		if (isset($this->userIDsToGroupID[$groupID])) {
			$this->userIDsToGroupID[$groupID][] = $action->getOption('user_id');
		}
	}

	/**
	 * @see	wcf\system\bot\action\type\IBotActionType::shutdownExecute()
	 */
	public function shutdownExecute() {
		parent::shutdownExecute();
		
		foreach ($this->userIDsToGroupID as $groupID => $userIDs) {
			$users = array();
			
			foreach($userIDs as $userID){
				$user = $this->getUserList()->search($userID);
				if(is_object($user)) $users[] = $user;
			}
			
			if (count($users) != 0) {	
				$objectAction = new UserAction($users, 'addToGroups', array(
					'groups' => array($groupID),
					'deleteOldGroups' => false,
					'addDefaultGroups' => false
				));
				$objectAction->executeAction();
			}
		}
	}
	
}
