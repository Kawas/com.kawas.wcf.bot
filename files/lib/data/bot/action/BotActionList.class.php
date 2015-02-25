<?php
namespace wcf\data\bot\action;
use wcf\data\DatabaseObjectList;

/**
 *
 *
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotActionList extends DatabaseObjectList {
	
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wcf\data\bot\action\BotAction';
	
	/**
	 * @see	wcf\data\DatabaseObjectList::$sqlSelects
	 */
	public $sqlSelects = 'bot_event.*, bot_action_type.*';
	
	/*
	 * @see	wcf\data\DatabaseObjectList::__construct()
	 */
	public function __construct() {
		$this->sqlJoins =  'LEFT JOIN wcf'.WCF_N.'_bot_event bot_event
							  ON bot_action.eventID = bot_event.eventID
							LEFT JOIN wcf'.WCF_N.'_bot_action_type bot_action_type
							  ON bot_action.actionTypeID = bot_action_type.actionTypeID';
							  
		parent::__construct();
	}                  
                          
}
