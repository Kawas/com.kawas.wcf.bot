<?php
namespace wcf\data\bot\event;
use wcf\data\DatabaseObjectEditor;
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
class BotEventEditor extends DatabaseObjectEditor {
	
	/**
	 * @see	wcf\data\DatabaseObjectEditor::$baseClass
	 */
	protected static $baseClass = 'wcf\data\bot\event\BotEvent';
	
}
