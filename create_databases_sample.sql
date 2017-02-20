

DROP TABLE IF EXISTS `todo_crud_php_users`;
CREATE TABLE `todo_crud_php_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `hashed_password` varchar(60) NOT NULL,
  `created_at` varchar(60) NOT NULL,
  `updated_at` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--



DROP TABLE IF EXISTS `todo_crud_php_notes`;
CREATE TABLE `todo_crud_php_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created_at` varchar(60) NOT NULL,
  `updated_at` varchar(60) NOT NULL,  
  `title` varchar(60) NOT NULL,
  `type` varchar(60) NOT NULL, 
  `content` text, 
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




