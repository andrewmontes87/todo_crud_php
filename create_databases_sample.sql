

DROP TABLE IF EXISTS `users`;
CREATE TABLE `stp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `hashed_password` varchar(60) NOT NULL,
  `favorite_series` varchar(60) NOT NULL,
  `favorite_captain` varchar(60) NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES (1,'username','salted and hashed password here','','');



DROP TABLE IF EXISTS `stp_characters`;
CREATE TABLE `stp_characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `species` varchar(60) NOT NULL,
  `name` varchar(60) NOT NULL, 
  `workplace` varchar(60) NOT NULL, 
  `morality` varchar(60) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




