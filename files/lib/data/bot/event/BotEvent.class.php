<?php
namespace wcf\data\bot\event;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * represents a bot event
 *
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotEvent extends DatabaseObject {
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'bot_event';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseIndexName
	 */
	protected static $databaseTableIndexName = 'eventID';
	
	/**
	 * @see	wcf\data\DatabaseObject::__construct()
	 */
	public function __construct($id, array $row = null, DatabaseObject $object = null) {
		parent::__construct($id, $row, $object);
    
		$this->parameters = explode(',', $this->parameters);
	}
}
