<?php
namespace wcf\system\bot;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\WCF;
use wcf\system\option\OptionHandler;
use wcf\data\option\Option;
use wcf\system\exception\SystemException;
use wcf\data\bot\action\BotAction;
use wcf\data\bot\event\BotEvent;

/**
 * Modificated optionhandler for the community bot
 *
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 * @category	Community Framework
 */
class BotOptionHandler{
    

	/**
	 * inctance of the orignal optionhandler
	 * @var wcf\system\option\OptionHandler
	 */
	protected $optionHandler;

	/**
	 * inctance of the action
	 * @var wcf\data\bot\BotAction
	 */
	public $action;

	/**
	 * inctance of the event
	 * @var wcf\data\bot\BotEvent
	 */
	public $event;

	/**
	 * @see	wcf\system\option\OptionHandler::__construct()
   	 */
	public function __construct($supportI18n, $languageItemPattern = '', $categoryName = ''){
		$this->optionHandler = new OptionHandler($supportI18n, $languageItemPattern, $categoryName);
	}
    

	/**
	 * @see	\wcf\system\option\IOptionHandler::validate()
	 */
	public function validate() {
		return $this->optionHandler->validate();
	}

	/**
	 * Returns the value of the origenal optionhandler data variable with the given name.
	 * 
	 * @param	string		$name
	 * @return	mixed
	 */
	public function __get($name) {
		return $this->optionHandler->$name;
	}

	/*
	 * initialize the handler
	 * @param wcf\data\bot\BotAction  $action
	 * @param wcf\data\bot\BotEvent   $event
	 */
	public function init(BotAction $action, BotEvent $event) {
		$this->action = $action;
		$this->event = $event;

		$sql = "SELECT * FROM wcf".WCF_N."_bot_option as actionOption
					LEFT JOIN wcf".WCF_N."_bot_option_type as optionType
						ON actionOption.optionTypeID = optionType.optionTypeID
					WHERE actionOption.actionID = ? AND optionType.hidden = 0
						ORDER BY actionTypeName, showOrder";
 
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($this->action->actionID));

		while ($row = $statement->fetchArray()) {
            if(empty($row['optionType'])) $row['optionType'] = 'text';
			$this->optionHandler->options[] = new Option(null, $row);
		}

	}

	/**
	 * Call a method of the original optionhandler  with the given name.
	 * 
	 * @param	string		$name
	 * @return	mixed
	 */
	public function __call($name, $arguments) {
		if (!method_exists($this->optionHandler, $name)) {
			throw new SystemException("unknown method '".$name."'");
		}

		return call_user_func_array(array($this->optionHandler, $name), $arguments);
	}

	/**
	 * @see	wcf\system\option\OptionHandler::__readUserInput()
	 */
	public function readUserInput(array &$source) {
		return $this->optionHandler->readUserInput($source);
	}

	/**
	 * Returns the options without categories.
	 * 
	 * @param	array<array>	$hiddenParameters
	 * @return	array<array>
	 */
	public function getOptions(array $hiddenParameters = array()) {
		if($this->action->useEventParameters) $hiddenParameters = array_flip($this->event->parameters);
		$options = array();

		foreach($this->optionHandler->options as $option){
			if(isset($hiddenParameters[$option->optionName])) continue;

			// get form element html
			$html = $this->getTypeObject($option->optionType)->getFormElement($option, (isset($this->optionValues[$option->optionName]) ? $this->optionValues[$option->optionName] : null));

			$options[] = array(
				'object' => $option,
				'html' => $html,
				'cssClassName' => $this->optionHandler->getTypeObject($option->optionType)->getCSSClassName()
			);
		}

		return $options;
	}


}
