<?php
namespace wcf\system\bot\action\type;
use wcf\system\WCF;
use wcf\system\bot\action\type\IBotActionType;
use wcf\data\bot\action\type\BotActionType;
use wcf\data\bot\action\BotAction;

/**
 * abstract class for multilanguage messages
 *
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	Bot test license <http://wbb-bot.com/forum/index.php/Thread/2-WBB-Bot/>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
abstract class AbstractMultiLanguageBotActionType extends AbstractUserBotActionType {

	/**
	 * actions 
	 * @var array<wcf\data\bot\BotAction>
	 */
	public $actions;

	/**
	 * @see	wcf\data\bot\actionType\IBotActionType::init()
	 */
	public function init() {
		$this->actions = array();
		parent::init();
	}

	/**
	 * @see	wcf\data\bot\actionType\IBotActionType::execute()
	 */
	public function execute(BotAction $action) {
		$this->actions[] = clone $action;
		parent::execute($action);
	}

	/** 
	 * Returns value of option by the given optionname
	 *
	 * @param string $optionname
	 * @param \wcf\data\bot\BotAction $action
	 * @return mixed
	 */
	public function getOption($optionName, BotAction $action) {
		$user = $this->getUser($action->getOption('user_id'));
		
		return $action->getOption($optionName, $user->languageID);
	}

}
