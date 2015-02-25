<?php
namespace wcf\system\package\plugin;
use wcf\system\WCF;
use wcf\system\package\PackageInstallationDispatcher;
use wcf\data\bot\event\BotEventList;
use wcf\data\bot\action\type\BotActionTypeList;

/**
 * Installs template actions
 * 
 * @author		Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license		creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package		com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotActionTemplatePackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::$className
	 */
	public $className = 'wcf\data\bot\action\BotActionEditor';
	
	/**
	 * @see	wcf\system\package\plugin\AbstractPackageInstallationPlugin::$tableName
	 */
	public $tableName = 'bot_action';
	
	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::$tagName
	 */
	public $tagName = 'action';
	
	/*
	 * event ids
	 * @var array<integer>
	 */
	public $eventNameToID;
	
	/*
	 * action type ids
	 * @var array<integer>
	 */
	public $actionTypeNameToID;
	
	/**
	 * @see	\wcf\system\package\plugin\AbstractPackageInstallationPlugin::install()
	 */
	public function __construct(PackageInstallationDispatcher $installation, $instruction = array()) {
		parent::__construct($installation, $instruction);
		
		$this->eventNameToID = array();
		$this->actionTypeNameToID = array();
		
		$eventList = new BotEventList();
		$eventList->readObjects();
		$actionTypeList = new BotActionTypeList();
		$actionTypeList->readObjects();
		
		foreach($eventList->getObjects() AS $object){
			$this->eventNameToID[$object->eventName] = $object->eventID;
		}
		
		foreach($actionTypeList->getObjects() AS $object){
			$this->actionTypeNameToID[$object->actionTypeName] = $object->actionTypeID;
		}
		
	}

	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::handleDelete()
	 */
	protected function handleDelete(array $items) {
		return;
	}
	
	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::prepareImport()
	 */
	protected function prepareImport(array $data) {
		$optionData = $data['elements'];
		unset($optionData['event']);
		unset($optionData['actiontype']);
	
		return array(
			'actionName' => $data['attributes']['name'],
			'eventID' => $this->eventNameToID[$data['elements']['event']],
			'actionTypeID' => $this->actionTypeNameToID[$data['elements']['actiontype']],
			'isDisabled' => 1,
			'isTemplate' => 1,
			'optionData' => $optionData
		);
	}
	
	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::findExistingItem()
	 */
	protected function findExistingItem(array $data) {
		$sql = "SELECT	*
			FROM	wcf".WCF_N."_".$this->tableName."
			WHERE	actionName = ?
				AND isTemplate = ?
				AND packageID = ?";
		$parameters = array(
			$data['actionName'],
			$data['isTemplate'],
			$this->installation->getPackageID()
		);
		
		return array(
			'sql' => $sql,
			'parameters' => $parameters
		);
	}
	
}
