<?php
namespace wcf\system\bot\action\type;
use wcf\system\WCF;
use wcf\system\bot\action\type\AbstractMultiLanguageBotActionType;
use wcf\data\bot\action\BotAction;
use wcf\data\conversation\ConversationAction;
use wcf\data\conversation\ConversationEditor;


/**
 * starts a conversation
 *
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	Bot test license <http://wbb-bot.com/forum/index.php/Thread/2-WBB-Bot/>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class NewConversationBotActionType extends AbstractMultiLanguageBotActionType {

	/**
	* @see	wcf\system\bot\action\type\IBotActionType::execute()
	*/
	public function shutdownExecute() {
		parent::shutdownExecute();
		
		foreach ($this->actions AS $action) {
			// save conversation
			if($action->getOption('user_id') != 0 AND $action->getOption('user_id') != $this->botObj->userID) {
				$data = array(
					'subject' => $this->getOption('subject', $action),
					'time' => TIME_NOW,
					'userID' => $this->botObj->userID,
					'username' => $this->botObj->username,
					'isDraft' => 0,
					'participantCanInvite' => true
				);


				$conversationData = array(
					'participants' => array($action->getOption('user_id')),
					'invisibleParticipants' => array(),
					'data' => $data,
					'messageData' => array(
						'message' => $this->getOption('message', $action),
						'enableBBCodes' => $action->getOption('enable_bbcodes'),
						'enableHtml' => $action->getOption('enable_html'),
						'enableSmilies' => $action->getOption('enable_smilies'),
						'showSignature' => $action->getOption('show_signature')
					)
				);

				$objectAction = new ConversationAction(array(), 'create', $conversationData);
				$conversation = $objectAction->executeAction();
                $conversation = $conversation['returnValues'];

				if($action->getOption('leave_conversation')) {
					$conversation = new ConversationEditor($conversation);
					$conversation->removeParticipant($this->botObj->userID);
					$conversation->updateParticipantSummary();
				}
			}
		}
	}

	
}
