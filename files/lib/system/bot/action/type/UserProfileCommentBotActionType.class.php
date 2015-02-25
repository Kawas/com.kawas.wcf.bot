<?php
namespace wcf\system\bot\action\type;
use wcf\system\WCF;
use wcf\system\bot\action\type\AbstractMultiLanguageBotActionType;
use wcf\data\bot\action\BotAction;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\comment\CommentAction;
use wcf\system\exception\SystemException;

/**
 * Creates a comment at wall of given user
 *
 * @author		Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license		creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package		com.kawas.wcf.bot
 * @category	Community Framework
 */
class UserProfileCommentBotActionType extends AbstractMultiLanguageBotActionType {
	/**
	 * objecttype ID of com.woltlab.wcf.user.profileComment
	 * @var integer
	 */
	public $objectTypeID;

	/**
	 * definition name
	 * @var strin
	 */
	public $definitionName = 'com.woltlab.wcf.comment.commentableContent';

	/**
	 * object type
	 * @var string
	 */
	public $objectType = 'com.woltlab.wcf.user.profileComment';

	/**
	 * @see	wcf\system\bot\action\type\IBotActionType::init()
	 */
	public function init() {
		parent::init();
		$this->objectTypeID = ObjectTypeCache::getInstance()->getObjectTypeIDByName($this->definitionName, $this->objectType);
	}

	/**
	 * @see	wcf\system\bot\action\type\IBotActionType::execute()
	 */
	public function shutdownExecute() {
		parent::shutdownExecute();

		foreach ($this->actions AS $action) {
			// catch exception from template system
			try {
				// create comment
				$objectAction = new CommentAction(array(), 'addComment', array(
					'data' => array(
					'objectTypeID' => $this->objectTypeID,
					'objectID' => $action->getOption('user_id'),
					'message' => $this->getOption('message', $action),
				)
				));
				$objectAction->validateAction();
				$objectAction->executeAction();
			} catch (SystemException $exception) {
				//do nothing
			}
		}
	}
		
}
