<?php
namespace wcf\system\package\plugin;
use wcf\system\WCF;
use wcf\system\package\plugin\BotEventPackageInstallationPlugin;
use wcf\system\package\plugin\BotActionTypePackageInstallationPlugin;

/**
 * Installs bot options
 * 
 * @author		Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license		creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package		com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotOptionTypePackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::$className
	 */
	public $className = 'wcf\data\bot\optionType\BotOptionTypeEditor';
	
	/**
	 * @see	wcf\system\package\plugin\AbstractPackageInstallationPlugin::$tableName
	 */
	public $tableName = 'bot_option_type';
	
	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::$tagName
	 */
	public $tagName = 'option';
	
	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::handleDelete()
	 */
	protected function handleDelete(array $items) {
		$sql = "DELETE FROM	wcf".WCF_N."_".$this->tableName."
					WHERE optionName = ? AND packageID = ?";
					
		$statement = WCF::getDB()->prepareStatement($sql);
		foreach ($items as $item) {
			$statement->execute(array(
				$item['attributes']['name'],
				$this->installation->getPackageID()
			));
		}
	}
	
	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::prepareImport()
	 */
	protected function prepareImport(array $data) {
		return array(
			'optionName' => $data['attributes']['name'],
			'eventName' => (isset($data['elements']['eventName']) ? $data['elements']['eventName'] : ''),
			'actionTypeName' => (isset($data['elements']['actionTypeName']) ? $data['elements']['actionTypeName'] : ''),
			'optionType' => $data['elements']['optionType'],
			'defaultValue' => (isset($data['elements']['default']) ? $data['elements']['default'] : ''),
			'showOrder' => intval(isset($data['elements']['showOrder']) ? $data['elements']['showOrder'] : 0),
			'supportI18n' => (isset($data['elements']['supportI18n']) ? $data['elements']['supportI18n'] : 0),
			'hidden' => (isset($data['elements']['hidden']) ? $data['elements']['hidden'] : 0),
			'selectOptions' => (isset($data['elements']['selectoptions']) ? $data['elements']['selectoptions'] : ''),
		);
	}
	
	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::findExistingItem()
	 */
	protected function findExistingItem(array $data) {
		$sql = "SELECT	*
					FROM	wcf".WCF_N."_".$this->tableName."
				WHERE	eventName = ?
					AND actionTypeName = ?
					AND optionName = ?
					AND packageID = ?";
			
		$parameters = array(
			$data['eventName'],
			$data['actionTypeName'],
			$data['optionName'],
			$this->installation->getPackageID()
		);
		
		return array(
			'sql' => $sql,
			'parameters' => $parameters
		);
	}

}
