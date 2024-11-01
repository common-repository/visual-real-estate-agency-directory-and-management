"dont run this file manually!"

CREATE TABLE IF NOT EXISTS `wp_sw_messages` 
( `idmessages` INT(11) NOT NULL AUTO_INCREMENT , 
 `related_key` VARCHAR(32) NULL DEFAULT NULL , 
 `date_sent` DATETIME NOT NULL ,
 `user_id_sender` INT(11) NULL DEFAULT NULL ,
 `user_id_receiver` INT(11) NULL DEFAULT NULL , 
 `email_sender` VARCHAR(100) NULL DEFAULT NULL ,
 `email_receiver` VARCHAR(100) NULL DEFAULT NULL , 
 `message` TEXT NOT NULL ,
 `is_readed` TINYINT(1) NOT NULL DEFAULT '0' ,
 PRIMARY KEY (`idmessages`)) 
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

