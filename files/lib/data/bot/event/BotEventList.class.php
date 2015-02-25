<?php
namespace wcf\data\bot\event;
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
class BotEventList extends DatabaseObjectList {
	
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wcf\data\bot\event\BotEvent';
	
}
