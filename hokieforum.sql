-- phpMyAdmin SQL Dump
-- version 4.0.10.17
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 15, 2016 at 03:54 AM
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
-- Table structure for table `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `topic_id`, `user_id`) VALUES
(7, 4, 7),
(13, 4, 2),
(16, 4, 3);


-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `location` point NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `topic_id`, `location`, `title`, `description`) VALUES
(1, 4, '\0\0\0\0\0\0\0��KqU�B@�Y��T�', 'Kelly''s Knob Trailhead', ''),
(2, 3, '\0\0\0\0\0\0\0���1��B@!�`D�\ZT�', 'Blacksburg''s Best Long Island', ''),
(3, 1, '\0\0\0\0\0\0\0L��w�B@\0��œ\ZT�', 'Be careful...', ''),
(4, 4, '\0\0\0\0\0\0\0C�K��B@\0��T�', 'Cedar Run Creek', ''),
(5, 1, '\0\0\0\0\0\0\0Va3��B@8�πzT�', 'Sketchy Longboard Run', ''),
(6, 2, '\0\0\0\0\0\0\0� ���B@���T�', 'Wicked good tacos', ''),
(7, 2, '\0\0\0\0\0\0\0oӟ�H�B@U[r\ZT�', 'Cabo Fish Taco is legit', ''),
(8, 1, '\0\0\0\0\0\0\0�i�a�B@\0\0�~�\ZT�', 'Chicken Hill parking lot', '');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE IF NOT EXISTS `replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post` text NOT NULL,
  `location` point DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `topic_id` (`topic_id`),
  KEY `topic_id_2` (`topic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`id`, `post`, `location`, `user_id`, `topic_id`, `date_created`) VALUES
(1, 'Long island ice teas are the bomb, especially at Sharkey''s!', NULL, 7, 3, '2016-10-30 17:17:01'),
(2, 'Great question tommylee!  I heard there''s this really awesome hill over by "The Hill" golf course on Graves Avenue. The only thing is that there''s tons of sticks and gravel plus it''s very steep, so wear the proper safety gear!\r\nFun Fact: the highest point in Blacksburg is on that golf course...', '\0\0\0\0\0\0\0Va3��B@8�πzT�', 1, 1, '2016-10-27 15:10:10'),
(3, 'Hey buddy!  All visitors are welcome here, glad to have you around.  As for the tacos, there''s a really great place on Prices Fork called Wicked Taco that''s also relatively cheap.  Be sure to check out the hooked deals before you go!', '\0\0\0\0\0\0\0� ���B@���T�', 1, 2, '2016-10-28 09:49:20'),
(4, 'Not so sure about beefy tacos, but Cabo on Main street has some damn good fish tacos', '\0\0\0\0\0\0\0oӟ�H�B@U[r\ZT�', 2, 2, '2016-10-28 09:50:12'),
(5, 'Yikes, longboarding down big hills is scary! I''m just visiting here, but my friend who goes here told me he goes longboarding around downtown a lot.', NULL, 4, 1, '2016-10-28 09:53:50'),
(7, 'North Main Street with out a doubt, coming down from the 7/11. But you have to be careful, because it''s pretty illegal. SWIM did it at 3am once, and it was glorious.. So I''ve heard.', '\0\0\0\0\0\0\0L��w�B@\0��œ\ZT�', 7, 1, '2016-11-01 21:55:58'),
(8, 'Maybe not so much ''hiking'' but there''s a pretty cool creek that sits in a valley just south of Blacksburg. It''s called Cedar Run, I tagged parking.', '\0\0\0\0\0\0\0C�K��B@\0��T�', 5, 4, '2016-11-01 22:05:38'),
(9, 'Chicken Hill behind Lane stadium is a nice hill. It can be a busy road though. The parking lot next to it is good too, that is where they hold the longboarding competition every year.', '\0\0\0\0\0\0\0�i�a�B@\0\0�~�\ZT�', 9, 1, '2016-11-03 16:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `post` text NOT NULL,
  `location` point DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `favorite_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `title`, `post`, `location`, `user_id`, `date_created`, `favorite_count`) VALUES
(1, 'What''s the best hill in Blacksburg to bomb on a longboard???', 'Hey guys! I''ve been living here for 4 years and I still can''t find any hills that are intense enough for my live-on-the-edge lifestyle. Can someone help me out?', NULL, 2, '2016-10-27 15:02:25', 0),
(2, 'Where can I get some good tacos around here?', 'Hi everyone! I''m not exactly sure if I''m supposed to be here considering I go to UVA, but I''m visiting for the week and I''m looking for somewhere to get good tacos.  There are tons of beefy cows around here, but I can''t seem to find any beefy tacos. Please help, I''m leaving in 2 days back to HooVille.', NULL, 4, '2016-10-28 09:43:01', 0),
(3, 'Best Long Island Iced Tea', 'Found it!! Hey everyone, if you want the best Long Island in Blacksburg it is at Sharkeys Bar and grill.', '\0\0\0\0\0\0\0���1��B@!�`D�\ZT�', 6, '2016-10-30 02:44:27', 0),
(4, 'Post your favorite local spot to go hiking!', 'So this thread isn''t really a question but more of a central location to find all of the cool hikes around Blacksburg. So if you''ve got a favorite, post it here. I''ll be unique and choose Kelly''s Knob. I love this spot because parts of the hike are wide open on the AT and it is not very well known. The tagged location is the trailhead. You can park at the fork in the road right up the street. Go North on the Appalachian Trail to see the coolest tree on this earth, South for Kelly''s Knob.', '\0\0\0\0\0\0\0��KqU�B@�Y��T�', 7, '2016-11-01 17:04:30', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `admin`) VALUES
(1, 'Admin', 'THFAdmin@vt.edu', 'Admin', 1),
(2, 'tommylee', 'tommylee@none.com', 'password', 0),
(3, 'seymore', 'moresey@none.com', 'butts', 0),
(4, 'hoosaidat', 'gouva@uva.edu', 'uva', 0),
(5, 'c0d3ster', 'cody.douglass@vt.edu', 'therealadmin', 1),
(6, 'HakunaTejada', 'alexart@vt.edu', 'password', 1),
(7, 'nick', 'ognick@vt.edu', 'nick', 1),
(8, 'morgan', 'sdkfjsl', 'hokie', 1),
(9, 'bs757', 'basostak@vt.edu', 'password', 0),
(10, 'fd', 'fd', 'fd', 0),
(11, 'Luke_Bluntwalker', 'Keegan.weiler@yahoo.com', 'Change', 0),
(12, 'Keegan', 'Keegan.weiler@yahoo.com', 'changr', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
