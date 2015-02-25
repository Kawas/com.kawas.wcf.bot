<?php
namespace wcf\system\cache\builder;
use wcf\data\bot\action\BotActionList;
use wcf\data\bot\action\type\BotActionTypeList;
use wcf\system\WCF;

/**
 * caches bot events, action types and actions
 * 
 * @author		Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license		creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package		com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotCacheBuilder extends AbstractCacheBuilder {
	/**
	 * @see	wcf\system\cache\builder\AbstractCacheBuilder::rebuild()
	 */
	protected function rebuild(array $parameters) {
		$data['actionList'] = new BotActionList();
		$data['actionList']->readObjects();
		$data['actionTypeList'] = new BotActionTypeList();
		$data['actionTypeList']->readObjects();
		$data['options'] = array();
		
		$sql = 'SELECT bot_option.actionID, bot_option_type.optionName, bot_option.optionValue FROM wcf'.WCF_N.'_bot_option bot_option
					LEFT JOIN wcf'.WCF_N.'_bot_option_type bot_option_type
						ON bot_option.optionTypeID = bot_option_type.optionTypeID';
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		while ($row = $statement->fetchArray()) {
			$data['options'][$row['actionID']][$row['optionName']] = $row['optionValue'];
		}
		

		return $data;
	}
}
