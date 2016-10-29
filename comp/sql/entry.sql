--
-- Table structure for table `entry`
--

CREATE TABLE `entry` (
  `entry_ID` int(11) NOT NULL,
  `session_ID` varchar(128) NOT NULL,
  `SSID` varchar(32) NOT NULL,
  `email` varchar(128) NOT NULL,
  `entry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `entry`
--
ALTER TABLE `entry`
  ADD PRIMARY KEY (`entry_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `entry`
--
ALTER TABLE `entry`
  MODIFY `entry_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
