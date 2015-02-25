<?php
namespace wcf\system\bot\action\type;
use wcf\system\WCF;
use wcf\system\bot\action\type\AbstractMultiLanguageBotActionType;
use wcf\data\bot\action\BotAction;
use wcf\system\mail\Mail;
use wcf\system\bbcode\MessageParser;
use wcf\system\exception\SystemException;

/**
 * Send a Email to given user.
 *
 * @author		Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license		creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package		com.kawas.wcf.bot
 * @category	Community Framework
 */
class SendMailToUserBotActionType extends AbstractMultiLanguageBotActionType {

	/**
	 * @see	wcf\system\bot\action\type\IBotActionType::execute()
	 */
	public function shutdownExecute() {
		parent::shutdownExecute();

		foreach ($this->actions AS $action) {
			$user = $this->getUser($action->getOption('user_id'));

			MessageParser::getInstance()->setOutputType('text/simplified-html');
			$message = MessageParser::getInstance()->parse($this->getOption('message', $action), $action->getOption('enable_smilies'), $action->getOption('enable_html'), $action->getOption('enable_bbcodes'));
						
			$mail = new Mail($user->email, $this->getOption('subject', $action), $message);	
			$mail->send();
		}
	}
		
}
