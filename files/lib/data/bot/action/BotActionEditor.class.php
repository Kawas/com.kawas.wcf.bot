<?php
namespace wcf\data\bot\action;
use wcf\data\DatabaseObjectEditor;
use wcf\system\WCF;
use wcf\data\IEditableCachedObject;
use wcf\system\cache\builder\BotCacheBuilder;

/**
 *
 *
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotActionEditor extends DatabaseObjectEditor implements IEditableCachedObject {
	
	/**
	 * @see	wcf\data\DatabaseObjectEditor::$baseClass
	 */
	protected static $baseClass = 'wcf\data\bot\action\BotAction';
	
	/**
	 * @see	wcf\data\IEditableObject::create()
	 */
	public static function create(array $parameters = array()) {
		if(!isset($parameters['packageID'])) $parameters['packageID'] = PACKAGE_ID;
		
		$optionData = array();
		if(isset($parameters['optionData'])) $optionData = $parameters['optionData'];
		unset($parameters['optionData']);
		
		$action = parent::create($parameters);
		
		$sql = "SELECT *
					FROM wcf".WCF_N."_bot_option_type
				WHERE actionTypeName = ? OR eventName = ?";
				   
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($action->actionTypeName, $action->eventName));
		
		$insertData = array();
		
		$sql = "INSERT INTO wcf".WCF_N."_bot_option (actionID,optionTypeID,optionValue) VALUES";
		while ($row = $statement->fetchArray()) {

			$value = $row['defaultValue'];
			if(isset($optionData[$row['optionName']])) $value = $optionData[$row['optionName']];

			$insertData[] = $action->actionID;
			$insertData[] = $row['optionTypeID'];
			$insertData[] = $value;

			$sql .= "(?,?,?),";
		}
		$sql = substr($sql, 0, -1);
		
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($insertData);
		
		return $action;
	}
	
	/**
	 * @see	wcf\data\IEditableObject::update()
	 */
	public function update(array $parameters = array()) {
		unset($parameters['optionData']);
		return parent::update($parameters);
	}
	
	/*
	 * Updates options.
	 * @param array<string> $options
	 */
	public static function updateOptions(array $options){
		$sql = "UPDATE wcf".WCF_N."_bot_option SET optionValue = ? WHERE optionID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		
		foreach($options AS $id => $optionValue) {
		  $statement->execute(array("$optionValue", $id));
		}
	}
	
	/**
	 * @see	wcf\data\IEditableCachedObject::resetCache()
	 */
	public static function resetCache() {
		BotCacheBuilder::getInstance()->reset();
	}
	
}
