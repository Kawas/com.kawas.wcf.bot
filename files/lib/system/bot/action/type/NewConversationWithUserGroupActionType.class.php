<?php
namespace wcf\system\bot\action\type;
use wcf\system\WCF;
use wcf\system\bot\action\type\IBotActionType;
use wcf\data\bot\action\type\BotActionType;
use wcf\data\bot\action\BotAction;
use wcf\data\conversation\ConversationAction;

/**
 * Starts conversation with a usergroup
 *
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	Bot test license <http://wbb-bot.com/forum/index.php/Thread/2-WBB-Bot/>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class NewConversationWithUserGroupActionType extends BotActionType implements IBotActionType {

	/**
	 * @see	wcf\data\system\action\type\IBotActionType::init()
	 */
	public function init() {}

	/**
	 * @see	wcf\data\system\action\type\IBotActionType::execute()
	 */
	public function execute(BotAction $action) {
		$userIDsByLanguageID = array();

		// get users
		$sql = "SELECT userTable.userID, userTable.languageID 
					FROM wcf".WCF_N."_user_to_group groupTable
						LEFT JOIN wcf".WCF_N."_user userTable
					ON groupTable.userID = userTable.userID
						WHERE groupTable.groupID = ?";

		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($action->getOption('group_id')));

		while ($row = $statement->fetchArray()) {
			if(!isset($userIDsByLanguageID[$row['languageID']])) $userIDsByLanguageID[$row['languageID']] = array();
			$userIDsByLanguageID[$row['languageID']][] = $row['userID'];
		}

		// start conversation divided by languages
		foreach ($userIDsByLanguageID AS $languageID => $userIDs) {
			$data = array(
				'subject' => $action->getOption('subject', $languageID),
				'time' => TIME_NOW,
				'userID' => $this->botObj->userID,
				'username' => $this->botObj->username,
				'isDraft' => 0,
				'participantCanInvite' => true
			);


			$conversationData = array(
				'participants' => array(),
				'invisibleParticipants' => array(),
				'data' => $data,
				'messageData' => array(
				'message' => $action->getOption('message', $languageID),
				'enableBBCodes' => $action->getOption('enable_bbcodes'),
				'enableHtml' => $action->getOption('enable_html'),
				'enableSmilies' => $action->getOption('enable_smilies'),
				'showSignature' => $action->getOption('show_signature')
			)
			);

			if($action->getOption('participants_visibility')) { 
				$conversationData['participants'] = $userIDs;
			} else { 
				$conversationData['invisibleParticipants'] = $userIDs;
			}

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

	/**
	 * @see	wcf\data\system\action\type\IBotActionType::shutdownExecut()
	 */
	public function shutdownExecute() {}

}
