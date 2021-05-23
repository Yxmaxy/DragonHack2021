DELIMITER $$

CREATE FUNCTION `UpdateUsername` (`usernameUser` VARCHAR(100), `emailUser` VARCHAR(100)) RETURNS VARCHAR(100)
BEGIN
		DECLARE response varchar(100) DEFAULT -1;
    	SELECT IF((SELECT 'prazno' FROM Users WHERE Email=emailUser AND UserName IS NULL)='prazno', "OK", "NOT") INTO response;
        IF (response = 'OK') THEN
       UPDATE `Users` SET `UserName`=usernameUser WHERE `Email`=emailUser;
       ELSE
       SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Username is already set!';
         END IF;
         RETURN response;
       	END$$
DELIMITER ;


CREATE TABLE `Users` (
  `ID` int(11) NOT NULL,
  `id_token` varchar(255) NOT NULL,
  `access_token` text NOT NULL,
  `UserName` varchar(100) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `picture` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `Users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `UserName` (`UserName`);


ALTER TABLE `Users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

