<?php
namespace wcf\data\bot\action;
use wcf\data\DatabaseObject;
use wcf\system\WCF;
use	wcf\system\exception\SystemException;
use wcf\system\bot\Bot;
use wcf\system\language\LanguageFactory;
use wcf\util\StringUtil;

/**
 *	Represents a bot action
 *
 * @author		Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license		creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package		com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotAction extends DatabaseObject {
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'bot_action';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseIndexName
	 */
	protected static $databaseTableIndexName = 'actionID';
	
	/**
	 * parameters given from event
	 * @var	array<array>
	 */
	protected $eventParameters;
	
	/**
	 * @see	wcf\data\DatabaseObject::__construct()
	 */
	public function __construct($id, array $row = null, DatabaseObject $object = null) {
		if ($id !== null) {
			$sql = "SELECT	*
						FROM	".static::getDatabaseTableName()." ".static::$databaseTableName."
							LEFT JOIN wcf".WCF_N."_bot_event bot_event
								ON bot_action.eventID = bot_event.eventID
							LEFT JOIN wcf".WCF_N."_bot_action_type bot_action_type
								ON bot_action.actionTypeID = bot_action_type.actionTypeID
					WHERE	".static::getDatabaseTableIndexName()." = ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array($id));
			$row = $statement->fetchArray();
			
			// enforce data type 'array'
			if ($row === false) $row = array();
		}
		else if ($object !== null) {
			$row = $object->data;
		}
	
		$this->eventParameters = array();
		$this->handleData($row);
	}
	
	/**
	 * Registers eventparameters.
	 * 
	 * @param	array<string>		$parameters
	 */
	public function registerEventParameters(array $parameters){
		$this->eventParameters = array_merge($this->eventParameters, $parameters);
	}
	
	/**
	 * Returns optionvalue.
	 * 
	 * @param	string	$optionName
	 * @param integer $languageID
	 * @return string
	 */
	public function getOption($optionName, $languageID = null){
		if (isset($this->eventParameters[$optionName]) AND $this->useEventParameters ) { 
			$optionValue = $this->eventParameters[$optionName];
		} elseif (Bot::getInstance()->options[$this->actionID][$optionName] != null) {
			$optionValue = Bot::getInstance()->options[$this->actionID][$optionName];
		} else {
			throw new SystemException("Bot option with index '".$optionName."' is not avaible");
		}
		
		if ($languageID !== null) {
			$languageObj = LanguageFactory::getInstance()->getLanguage($languageID);
			//if(is_object($languageObj))
			$optionValue = $languageObj->get($optionValue);
		}
			
		foreach ($this->eventParameters as $key => $value) {
			//if(is_object($languageObj)) $value = $languageObj->get($value);
			$optionValue = str_replace('{'.StringUtil::toUpperCase($key).'}', htmlspecialchars($value), $optionValue);
		}
		foreach (Bot::getInstance()->options[$this->actionID] as $key => $value) {
			//if(is_object($languageObj)) $value = $languageObj->get($value);
			$optionValue = str_replace('{'.StringUtil::toUpperCase($key).'}', htmlspecialchars($value), $optionValue);
		}
		
		return $optionValue;
	}
	
	/**
	 * Executes the action.
	 */
	public function execute($userManipulation = true){
		if($userManipulation) Bot::getInstance()->manipulateUser();
		Bot::getInstance()->actionTypeList->objects[$this->actionTypeName]->execute($this);
		if($userManipulation) Bot::getInstance()->restoreUser();
	}
	
}
