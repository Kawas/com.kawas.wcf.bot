<?php
use wcf\system\WCF;

/**
 * @author	Oskar Schaffner
 * @copyright	2013-2014 Oskar Schaffner
 * @license	creative commons <http://creativecommons.org/licenses/by-sa/4.0/deed.de>
 * @package	com.kawas.wcf.bot
 */

$package = $this->installation->getPackage();

// set install date
$sql = "UPDATE	wcf".WCF_N."_option
	SET	optionValue = ?
	WHERE	optionName = ?";
$statement = WCF::getDB()->prepareStatement($sql);
$statement->execute(array($package->installDate, 'wcf_bot_install_date'));

// set default permissions
$sql = "Update 	wcf".WCF_N."_user_group_option_value
			SET optionValue = 0
			WHERE optionID =
				(SELECT			optionID
				FROM			wcf".WCF_N."_user_group_option
				WHERE			optionName ='admin.general.bot.canManageBot')";
$statement = WCF::getDB()->prepareStatement($sql);
$statement->execute();
$sql = "INSERT INTO 	wcf".WCF_N."_user_group_option_value
			(groupID, optionID, optionValue)
				SELECT			4, optionID, 1
				FROM			wcf".WCF_N."_user_group_option
				WHERE			optionName ='admin.general.bot.canManageBot'
			ON DUPLICATE KEY UPDATE optionvalue=1";
$statement = WCF::getDB()->prepareStatement($sql);
$statement->execute();
