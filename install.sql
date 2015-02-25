DROP TABLE IF EXISTS wcf1_bot_action;
CREATE TABLE wcf1_bot_action (
  actionID int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  actionTypeID int(10) NOT NULL,
  eventID int(10) NOT NULL,
  actionName varchar(255) NOT NULL,
  useEventParameters tinyint(1) NOT NULL DEFAULT 1,
  isDisabled tinyint(1) NOT NULL DEFAULT 0,
  isTemplate tinyint(1) NOT NULL DEFAULT 0,
  packageID int(10) NOT NULL
);

DROP TABLE IF EXISTS wcf1_bot_action_type;
CREATE TABLE wcf1_bot_action_type (
  actionTypeID int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  actionTypeName varchar(255) NOT NULL,
  className varchar(255) NOT NULL,
  isRisky tinyint(1) NOT NULL DEFAULT 0,
  packageID int(10) NOT NULL
);

DROP TABLE IF EXISTS wcf1_bot_event;
CREATE TABLE wcf1_bot_event (
  eventID int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  eventName varchar(255) NOT NULL,
  parameters text NOT NULL,
  packageID int(10) NOT NULL
);

DROP TABLE IF EXISTS wcf1_bot_option;
CREATE TABLE wcf1_bot_option (
  optionID int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  actionID int(10) NOT NULL,
  optionTypeID int(10) NOT NULL,
  optionValue text NOT NULL
);

DROP TABLE IF EXISTS wcf1_bot_option_type;
CREATE TABLE wcf1_bot_option_type (
  optionTypeID int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  optionName varchar(255) NOT NULL,
  actionTypeName varchar(255) NOT NULL DEFAULT '',
  eventName varchar(255) NOT NULL DEFAULT '',
  optionType varchar(255) NOT NULL,
  defaultValue text NOT NULL,
  showOrder int(10) NOT NULL,
  supportI18n tinyint(1) NOT NULL,
  hidden tinyint(1) NOT NULL DEFAULT 0,
  selectOptions mediumtext NOT NULL,
  packageID int(10) NOT NULL
);

DROP TABLE IF EXISTS wcf1_bot_action_log;
CREATE TABLE wcf1_bot_action_log (
  actionLogID int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  actionID int(10) NOT NULL,
  userID int(10) NOT NULL DEFAULT 0
);

ALTER TABLE wcf1_bot_action ADD FOREIGN KEY (actionTypeID) REFERENCES wcf1_bot_action_type (actionTypeID) ON DELETE CASCADE;
ALTER TABLE wcf1_bot_action ADD FOREIGN KEY (eventID) REFERENCES wcf1_bot_event (eventID) ON DELETE CASCADE;
ALTER TABLE wcf1_bot_option ADD FOREIGN KEY (actionID) REFERENCES wcf1_bot_action (actionID) ON DELETE CASCADE;
ALTER TABLE wcf1_bot_action_log ADD FOREIGN KEY (actionID) REFERENCES wcf1_bot_action (actionID) ON DELETE CASCADE;
