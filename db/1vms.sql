-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2019 at 01:51 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vms`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `SickLeaveSort`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SickLeaveSort` ()  BEGIN

	declare id int default 0;
	declare cnt decimal(18,2) default 0;
	declare maxcnt int default 0;
	declare string text;
	declare dentry datetime;
	declare dexit datetime;
	declare dcomment text;
	declare dremainder text;
	declare done int default false;
	
	declare cur cursor for 
    	select PersonId,Comment from temppersonsickleave where Comment > '';
	declare continue handler for not found set done=true;
    
	open cur;
    
		read_loop: LOOP
			Fetch cur into id,string;
			if done then
				 leave read_loop;
			end if;
			set maxcnt=round(char_length(string)-char_length(replace(string,'~~~',''))) / char_length('~~~');
			set cnt =0;
            while cnt <= maxcnt do
                    set dentry=substring(string,locate('/',string,1)-2,8);
                    if round(char_length(string)-char_length(replace(string,'/','')))/char_length('/')=2 then
                        set dexit=substring(string,locate('/',string,1)-2,8);
                    end if;
                    if round(char_length(string)-char_length(replace(string,'/','')))/char_length('/')>2 then
                        set dexit=substring(string,char_length(substring_index(string,'/',3))-1,8);
                    end if;
                    set dcomment=substring_index(string,'~~~',1);
                    set dremainder=substring(string,char_length(substring_index(string,'~~~',1))+4,char_length(string));
                    set string=dremainder;
                    insert into personsickleave(PersonId,StartDate,FinishDate,Comment)values(id,STR_TO_DATE(dentry,'%d/%m/%Y'),STR_TO_DATE(dexit,'%d/%m/%Y'),dcomment);
                    set cnt=cnt+1;
            end while;	
		end LOOP;
	close cur;
END$$

DROP PROCEDURE IF EXISTS `Sort`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Sort` ()  BEGIN

	declare id int default 0;
	declare cnt decimal(18,2) default 0;
	declare maxcnt int default 0;
	declare string text;
	declare dentry datetime;
	declare dexit datetime;
	declare dcomment text;
	declare dremainder text;
	declare done int default false;
	
	declare cur cursor for 
    	select PersonId,Comment from temppersonleave where Comment > '';
	declare continue handler for not found set done=true;
    
	open cur;
    
		read_loop: LOOP
			Fetch cur into id,string;
			if done then
				 leave read_loop;
			end if;
			set maxcnt=round(char_length(string)-char_length(replace(string,'~~',''))) / char_length('~~');
			set cnt =0;
            while cnt <= maxcnt do
                    set dentry=substring(string,locate('/',string,1)-2,8);
                    if round(char_length(string)-char_length(replace(string,'/','')))/char_length('/')=2 then
                        set dexit=substring(string,locate('/',string,1)-2,8);
                    end if;
                    if round(char_length(string)-char_length(replace(string,'/','')))/char_length('/')>2 then
                        set dexit=substring(string,char_length(substring_index(string,'/',3))-1,8);
                    end if;
                    set dcomment=substring_index(string,'~~',1);
                    set dremainder=substring(string,char_length(substring_index(string,'~~',1))+4,char_length(string));
                    set string=dremainder;
                    insert into personleave(PersonId,StartDate,FinishDate,Comment)values(id,STR_TO_DATE(dentry,'%d/%m/%Y'),STR_TO_DATE(dexit,'%d/%m/%Y'),dcomment);
                    set cnt=cnt+1;
            end while;	
		end LOOP;
	close cur;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `LogId` int(11) NOT NULL AUTO_INCREMENT,
  `ClientId` int(11) NOT NULL,
  `LoggedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Dump` varchar(2000) NOT NULL,
  PRIMARY KEY (`LogId`),
  KEY `UserId` (`ClientId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `logs`:
--   `UserId`
--       `users` -> `UserId`
--   `ActiveId`
--       `userlogin` -> `ActiveId`
--   `ActiveId`
--       `userlogin` -> `ActiveId`
--   `UserId`
--       `users` -> `UserId`
--

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`UserId`),
  ADD CONSTRAINT `logs_ibfk_2` FOREIGN KEY (`ActiveId`) REFERENCES `userlogin` (`ActiveId`),
  ADD CONSTRAINT `vms_activeid` FOREIGN KEY (`ActiveId`) REFERENCES `userlogin` (`ActiveId`),
  ADD CONSTRAINT `vms_userid` FOREIGN KEY (`UserId`) REFERENCES `users` (`UserId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
