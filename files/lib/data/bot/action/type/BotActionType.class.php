<?php
namespace wcf\data\bot\action\type;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 *
 *
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotActionType extends DatabaseObject {
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'bot_action_type';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseIndexName
	 */
	protected static $databaseTableIndexName = 'actionTypeID';
	
	/**
	 * object of the bot
	 * @var \wcf\data\bot\Bot
	 */
	public $botObj;

}
