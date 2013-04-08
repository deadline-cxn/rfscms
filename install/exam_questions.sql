-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 14, 2012 at 10:15 AM
-- Server version: 5.5.28-0ubuntu0.12.04.3
-- PHP Version: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `trainweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `exam_questions`
--

CREATE TABLE IF NOT EXISTS `exam_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `exam_sequence` int(11) NOT NULL,
  `type` text NOT NULL,
  `intro` text NOT NULL,
  `question` text NOT NULL,
  `question_image` text NOT NULL,
  `correct_answer` text NOT NULL,
  `choice_1` text NOT NULL,
  `choice_2` text NOT NULL,
  `choice_3` text NOT NULL,
  `choice_4` text NOT NULL,
  `choice_5` text NOT NULL,
  `choice_6` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `exam_questions`
--

INSERT INTO `exam_questions` (`id`, `exam_id`, `exam_sequence`, `type`, `intro`, `question`, `question_image`, `correct_answer`, `choice_1`, `choice_2`, `choice_3`, `choice_4`, `choice_5`, `choice_6`) VALUES
(1, 1, 1, 'multiple_choice', 'Worse Punishment?\r\n        An Air Force cargo plane was preparing for departure from Thule Air Base in Greenland. They were waiting for the truck to arrive to pump out the aircraft''s sewage holding tank.\r\n        The Aircraft Commander was in a hurry, the truck was late in arriving, and the Airman performing the job was extremely slow in getting the tank pumped out.\r\n        When the commander berated the Airman for his slowness and promised punishment, the Airman responded: "Sir, I have no stripes, it is 20 below zero, I''m stationed in Greenland, and I am pumping sewage out of airplanes. Just what are you going to do to punish me?"', 'Is this an image of a white exclamation mark inside of an orange circle?', 'images/icons/alert.png', '2', 'No', 'Yes', 'Never', 'Absolutely not', 'No, It is a red question mark inside of a green triangle.', ''),
(2, 1, 2, 'fill_in_blank', 'Military etiquette\r\n        Officer: Soldier, do you have change for a dollar? \r\n        Soldier: Sure, buddy.\r\n        Officer: That''s no way to address an officer! Now let''s try it again. Do you have change for a dollar?\r\n        Soldier: No, SIR!', 'Ask not what your -a- can do for you. Ask what you can do for your -b-.\n\n', '', 'country', 'farm', '', '', '', '', ''),
(3, 1, 4, 'fill_in_the_blank_dropdown\r\n', '\nCommunication Breakdown...\n        The reason the Army, Navy, Air Force, and Marines squabble among themselves is that they don''t speak the same language. For example, take a simple phrase like, "Secure the building." The Army will put guards around the place. The Navy will turn out the lights and lock the doors. The Air Force will take out a 5-year lease with an option to buy. The Marines will kill everybody inside and make it a command post.', 'There are -a- types of people. Those who understand -b-, and those who do not. -c-', '', '10,binary', 'Four', '5', '10', '01', '11', 'binary'),
(4, 1, 3, 'true_false', '', 'Humans need oxygen in order to survive.', '', 'true', '', '', '', '', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
