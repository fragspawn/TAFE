-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2016 at 07:00 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

--
-- Database: `student_votes`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` double NOT NULL,
  `fname` varchar(128) NOT NULL,
  `lname` varchar(128) NOT NULL,
  `salutation` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `fname`, `lname`, `salutation`) VALUES
(5105490515, '', '', ''),
(9103077704, '', '', ''),
(1105393115, '', '', ''),
(2100110115, '', '', ''),
(3103779415, '', '', ''),
(8100160612, '', '', ''),
(4100182116, '', '', ''),
(2101920515, '', '', ''),
(6190023102, '', '', ''),
(6100213116, '', '', ''),
(8104334014, '', '', ''),
(3105793414, '', '', ''),
(4100681916, '', '', ''),
(5102640913, '', '', ''),
(7103292214, '', '', ''),
(2103279814, '', '', ''),
(8104930815, '', '', ''),
(3105274215, '', '', ''),
(3105645008, '', '', ''),
(8100558216, '', '', ''),
(3100220316, '', '', ''),
(1100125716, '', '', ''),
(6107683511, '', '', ''),
(3100307116, '', '', ''),
(7060190504, '', '', ''),
(6103823213, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venue_url` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venue_url`) VALUES
('pignwhistle.com.au'),
('thefox.com.au'),
('theshipinn.com.au'),
('ploughinn.com.au');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL,
  `student_no` double NOT NULL,
  `vote_choice` varchar(128) NOT NULL,
  `vote_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
