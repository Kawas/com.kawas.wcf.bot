<?php
namespace wcf\data\bot\optionType;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * Represents a bot option type
 *
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotOptionType extends DatabaseObject {
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'bot_option_type';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseIndexName
	 */
	protected static $databaseTableIndexName = 'optionTypeID';

}
