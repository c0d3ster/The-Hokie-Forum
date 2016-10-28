-- phpMyAdmin SQL Dump
-- version 4.0.10.17
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 28, 2016 at 09:54 AM
-- Server version: 5.5.52
-- PHP Version: 5.6.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hokieforum`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `topic_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `topic_id` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE IF NOT EXISTS `replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post` text NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `topic_id` (`topic_id`),
  KEY `topic_id_2` (`topic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`id`, `post`, `location`, `user_id`, `topic_id`, `date_created`) VALUES
(1, 'What''s up boss.\r\n\r\nUndoubtably N Main street. But you gotta watch out because it''s pretty illegal. SWIM went down at 3am and it was glorious.', NULL, 3, 1, '2016-10-27 15:10:10'),
(2, 'Great question tommylee!  I heard there''s this really awesome hill over by "The Hill" golf course on Graves Avenue. The only thing is that there''s tons of sticks and gravel plus it''s very steep, so wear the proper safety gear!\r\nFun Fact: the highest point in Blacksburg is on that golf course...', NULL, 1, 1, '2016-10-27 15:10:10'),
(3, 'Hey buddy!  All visitors are welcome here, glad to have you around.  As for the tacos, there''s a really great place on Prices Fork called Wicked Taco that''s also relatively cheap.  Be sure to check out the hooked deals before you go!', NULL, 1, 2, '2016-10-28 09:49:20'),
(4, 'Not so sure about beefy tacos, but Cabo on Main street has some damn good fish tacos!', NULL, 2, 2, '2016-10-28 09:50:12'),
(5, 'Yikes, longboarding down big hills is scary! I''m just visiting here, but my friend who goes here told me he goes longboarding around downtown a lot.', NULL, 4, 1, '2016-10-28 09:53:50');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `post` text NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `title`, `post`, `location`, `user_id`, `date_created`) VALUES
(1, 'What''s the best hill in Blacksburg to bomb on a longboard?', 'Hey guys! I''ve been living here for 4 years and I still can''t find any hills that are intense enough for my live-on-the-edge lifestyle. \r\n\r\nCan someone help me out?', NULL, 2, '2016-10-27 15:02:25'),
(2, 'Where can I get some good tacos around here?', 'Hi everyone! I''m not exactly sure if I''m supposed to be here considering I go to UVA, but I''m visiting for the week and I''m looking for somewhere to get good tacos.  There are tons of beefy cows around here, but I can''t seem to find any beefy tacos. Please help, I''m leaving in 2 days back to HooVille.', NULL, 4, '2016-10-28 09:43:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `admin`) VALUES
(1, 'Admin', 'THFAdmin@vt.edu', 'Admin', 1),
(2, 'tommylee', 'tommylee@none.com', 'password', 0),
(3, 'seymore', 'moresey@none.com', 'butts', 0),
(4, 'hoosaidat', 'gouva@uva.edu', 'uva', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`);

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`);

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`);

--
-- Constraints for table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
