<?php
namespace wcf\system\event\listener;
use wcf\system\event\IEventListener;
use wcf\system\WCF;
use wcf\system\bot\Bot;

/**
 * executes the newUser bot event
 * 
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotRegisterFormListener implements IEventListener {
	/**
	 * @see	wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (Bot::getInstance()->isEnabled()) {
			$sql = "SELECT * FROM wcf".WCF_N."_user
						WHERE username = ?";

			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array($eventObj->username));
			$parameters['user_id'] = $statement->fetchArray()["userID"];
			$parameters['username'] = $eventObj->username;

			Bot::getInstance()->fireActions('newUser', $parameters);
		}
	}    
   
}
