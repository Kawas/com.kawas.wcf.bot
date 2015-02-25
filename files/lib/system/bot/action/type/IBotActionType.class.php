<?php
namespace wcf\system\bot\action\type;
use wcf\data\bot\action\BotAction;

/**
 *
 *
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	Bot test license <http://wbb-bot.com/forum/index.php/Thread/2-WBB-Bot/>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
interface IBotActionType{

	/**
	 * init the action
	 * 
	 * @param	object		$action
	 * @return	bool
	 */
	public function init();
	
	/**
	 * Executes the action
	 * 
	 * @param	<wcf\data\bot\action\BotAction>		$action
	 * @return	bool
	 */
	public function execute(BotAction $action);
	
	/**
	 * Executes the action
	 * 
	 * @param	object		$action
	 * @return	bool
	 */
	public function shutdownExecute();
	
	
}
