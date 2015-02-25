<?php
namespace wcf\system\cronjob;
use wcf\system\bot\Bot;
use wcf\data\bot\action\log\BotActionLogEditor;
use wcf\data\user\User;
use wcf\util\DateUtil;
use wcf\data\cronjob\Cronjob;
use wcf\system\WCF;
use wcf\util\StringUtil;
use wcf\system\event\EventHandler;


/**
 * execute bot events: birthdayOfUser, dateX, monthly, yearly, userHasOverXLikesReceived, userHasOverXActivityPoints, userHasOverXProfileHits, userIsMemberXDays
 * 
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotCronjob extends AbstractCronjob {

    /**
     * activates multiply execution of events: birthdayOfUser, dateX, monthly, yearly, userHasOverXLikesReceived,
     * userHasOverXActivityPoints, userHasOverXProfileHits, userIsMemberXDays
     * @var boolean
     */
    const DEBUG_MODE = WCF_BOT_DEBUG_MODE;

    /**
     * max execution time
     * @var integer
     */
    const MAX_EXECUTION_TIME = WCF_BOT_MAX_EXECUTION_TIME;

    /**
     * execution start time of cronjob
     * @var integer
     */
    public $startExecutionTime;
    
	/**
	 * @see	\wcf\system\cronjob\ICronjob::execute()
	 */
	public function execute(Cronjob $cronjob) {
		if (Bot::getInstance()->isEnabled()) {
			$this->startExecutionTime = microtime();

			if(date('Y-m-d', TIME_NOW) != gmdate('Y-m-d', $cronjob->lastExec) OR self::DEBUG_MODE){
			  
				// birthdayOfUser event
				$userOptionID = User::getUserOptionID('birthday');
				
				$sql = "SELECT	user.userID AS user_id, user.username, option_value.userOption" . $userOptionID . " as userage
					FROM	wcf".WCF_N."_user_option_value option_value
					  LEFT JOIN wcf".WCF_N."_user user
						ON user.userID = option_value.userID
					WHERE 	DAY(option_value.userOption" . $userOptionID . ") = DAY(NOW())
						AND MONTH(option_value.userOption" . $userOptionID . ") = MONTH(NOW())";
				$statement = WCF::getDB()->prepareStatement($sql);
				$statement->execute();
				
				while ($row = $statement->fetchArray()) {
					$row['userage'] = DateUtil::getAge($row['userage']);
					Bot::getInstance()->fireActions('birthdayOfUser', $row);
				}
				
				// dateX event
				$actions = Bot::getInstance()->getActions('dateX');
				foreach ($actions AS $action) {
					if ($action->getOption('execution_date') == gmdate('Y-m-d', TIME_NOW)) {
						$action->execute();
					}
				}
				
				
				$day = gmdate('d', TIME_NOW);
				$month = gmdate('m', TIME_NOW);
				
				// monthly event
				$actions = Bot::getInstance()->getActions('monthly');
				foreach ($actions AS $action) {
					if ($action->getOption('execution_day') == $day) $action->execute();
				}
				
				// yearly event
				$actions = Bot::getInstance()->getActions('yearly');
				foreach ($actions AS $action) {
					if ($action->getOption('execution_day') == $day AND $action->getOption('execution_month') == $month ) $action->execute();
				}
			}

			// userHasOverXLikesReceived event
			$this->userHasOverX('likes_received', 'likesReceived');

			// userHasOverXActivityPoints event
			$this->userHasOverX('activity_points', 'activityPoints');

			// userHasOverXProfileHits event
			$this->userHasOverX('profile_hits', 'profileHits');
            

			// userIsMemberXDays event
			$actions = Bot::getInstance()->getActions('userIsMemberXDays');

			foreach($actions as $action) {
				$action->registerEventParameters(array(
					'registration_date' => TIME_NOW - ($action->getOption('days') * 86400)
				));
			}

			$this->userHasOverX('registration_date', 'registrationDate', true, $actions);
			
			// userIsInactiveXDays event
			$actions = Bot::getInstance()->getActions('userIsInactiveXDays');

			foreach($actions as $action) {
				$action->registerEventParameters(array(
					'last_activity_time' => TIME_NOW - ($action->getOption('days') * 86400)
				));
			}

			$this->userHasOverX('last_activity_time', 'lastActivityTime', true, $actions);
            
            // call execute event for addinational bot events
            EventHandler::getInstance()->fireAction($this, 'execute');

		}
	}
	
	/**
	 * general function for userHasOverX events
	 *
	 * @param string	$optionName
	 * @param string 	$subject
	 * @param boolean 	$reverseOperator
	 * @param object	array</wcf/system/bot/action/type/IBotActionType> $actions
	 */
	public function userHasOverX($optionName, $subject, $reverseOperator = false, $actions = null){
		if($this->isExecutionTimeExceeded()) return;
		
		if($actions == null) {
			$actions = Bot::getInstance()->getActions('userHasOverX' . StringUtil::firstCharToUpperCase($subject));
		}

		if(count($actions) == 0) return;
		
		foreach ($actions AS $action) {
			$sql = "SELECT user.userID, user.username, user." .$subject. ", action_log.actionID
						FROM wcf".WCF_N."_user user
							LEFT JOIN wcf".WCF_N."_bot_action_log action_log
						ON action_log.userID = user.userID AND action_log.actionID = ?
							WHERE user." .$subject. " " .($reverseOperator  ? "<=" : " >="). " ? ".
								(self::DEBUG_MODE  ? "" : " AND actionID IS NULL");


			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array($action->actionID, intval($action->getOption($optionName))));

			while ($row = $statement->fetchArray()) {
				if($row['userID'] == Bot::getInstance()->userID) continue;

				if(!self::DEBUG_MODE) {
					BotActionLogEditor::create(array(
						'actionID' => $action->actionID,
						'userID' => $row['userID']
					));
				}

				$row['user_id'] = $row['userID'];
				unset($row['actionID']);
				unset($row['userID']);

				$action->registerEventParameters($row);
				$action->execute();

				if($this->isExecutionTimeExceeded()) return;
			}
		}
	}
	
	/**
     * return true if execution time if exceeded
     *
     * @return boolean
     */
	public function isExecutionTimeExceeded() {
		if((microtime() - $this->startExecutionTime) > self::MAX_EXECUTION_TIME) return true;
		return false;
	}
	
}
