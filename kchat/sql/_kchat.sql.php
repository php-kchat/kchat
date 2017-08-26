-- No Direct Access --<?php die; ?>

--
-- Database: `kchat`
--

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%cache`
--

CREATE TABLE `%dbprefix%cache` (
  `id` int(11) NOT NULL,
  `fname` varchar(32) DEFAULT NULL,
  `lname` varchar(32) DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `uname` varchar(32) DEFAULT NULL,
  `group` varchar(32) DEFAULT NULL,
  `process` int(3) DEFAULT NULL,
  `value` int(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%department`
--

CREATE TABLE `%dbprefix%department` (
  `id` int(11) NOT NULL,
  `dept` varchar(20) DEFAULT NULL,
  `discription` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `%dbprefix%department`
--

INSERT INTO `%dbprefix%department` VALUES
(1, 'Admin', 'Head of Department'),
(2, 'support', 'support people'),
(3, 'IT', 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%groups`
--

CREATE TABLE `%dbprefix%groups` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `groupid` varchar(32) NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT 'Undefined',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `%dbprefix%groups`
--

INSERT INTO `%dbprefix%groups` VALUES
('NO_GROUP', '00000000000000000000000000000000', 'Undefined', now());

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%group_users`
--

CREATE TABLE `%dbprefix%group_users` (
  `id` int(11) NOT NULL,
  `grupid` varchar(32) NOT NULL,
  `users` varchar(32) NOT NULL,
  `lastseen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seens` int(11) NOT NULL DEFAULT '0',
  `notify` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%guest`
--

CREATE TABLE `%dbprefix%guest` (
  `id` varchar(32) NOT NULL,
  `guest_id` varchar(32) NOT NULL,
  `group_id` varchar(32) DEFAULT NULL,
  `ip` varchar(64) NOT NULL,
  `country_code` varchar(32) DEFAULT NULL,
  `time_zone` varchar(64) DEFAULT NULL,
  `latitude` int(11) DEFAULT NULL,
  `longitude` int(11) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%msgs`
--

CREATE TABLE `%dbprefix%msgs` (
  `id` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `msg` text NOT NULL,
  `grp_id` varchar(32) NOT NULL,
  `sender_id` varchar(32) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%pusers`
--

CREATE TABLE `%dbprefix%pusers` (
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `uname` varchar(20) NOT NULL,
  `secret` varchar(128) NOT NULL,
  `depart` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%role`
--

CREATE TABLE `%dbprefix%role` (
  `id` int(11) NOT NULL,
  `dept` varchar(20) DEFAULT NULL,
  `discription` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `%dbprefix%role`
--

INSERT INTO `%dbprefix%role` VALUES
(1, 'admin', 'user with all privileges');

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%setting`
--

CREATE TABLE `%dbprefix%setting` (
  `id` int(3) NOT NULL,
  `key` varchar(32) DEFAULT NULL,
  `value` varchar(256) DEFAULT NULL,
  `option` varchar(20) DEFAULT NULL,
  `tab` varchar(20) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  `css` varchar(32) DEFAULT NULL,
  `selecter` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `%dbprefix%setting`
--

INSERT INTO `%dbprefix%setting` VALUES
(1, 'headcolor1', '85D4FF', 'Head Color', 'Color Setting', 'color', 'background-color', '#KChat_heading'),
(2, 'bordercolor1', 'FFFFFF', 'body Color', 'Color Setting', 'color', 'background-color', '#KChat_scroll_panel'),
(3, 'mesgboxcolor1', 'FFFFFF', 'Massage Box Color', 'Color Setting', 'color', 'background-color', '#KChat_textarea'),
(7, 'headclr', '000000', 'Heading Color', 'Color Setting', 'color', 'color', '#KChat_heading_title'),
(8, 'headbdrc', 'BFFFC5', 'Right Box Color', 'Color Setting', 'color', 'background-color', '.message1'),
(9, 'bodybdrp', '002904', 'Right Box text', 'Color Setting', 'color', 'color', '.message1'),
(10, 'bodybdrc', '031A00', 'Right Border Color', 'Color Setting', 'color', 'border-color', '.message1'),
(11, 'inboxbdrp', '1', 'Right Border size', 'Color Setting', 'pixel', 'border-width', '.message1'),
(12, 'inboxbdrc', '30FF65', 'Right anchor Color', 'Color Setting', 'color', 'color', '.message1 a'),
(13, 'headbdrcx', 'FFCFBF', 'Left Box Color', 'Color Setting', 'color', 'background-color', '.message0'),
(14, 'bodybdrpx', '3D0000', 'Left Box text', 'Color Setting', 'color', 'color', '.message0'),
(15, 'bodybdrcx', '0A0000', 'Left Border Color', 'Color Setting', 'color', 'border-color', '.message0'),
(16, 'inboxbdrpx', '1', 'Left Border size', 'Color Setting', 'pixel', 'border-width', '.message0'),
(17, 'inboxbdrcx', 'FF0000', 'Left anchor Color', 'Color Setting', 'color', 'color', '.message0 a');

-- --------------------------------------------------------

--
-- Stand-in structure for view `%dbprefix%temp`
-- (See below for the actual view)
--
CREATE TABLE `%dbprefix%temp` (
`id` int(11)
,`fname` varchar(32)
,`lname` varchar(32)
,`time` int(11)
,`uname` varchar(32)
,`group` varchar(32)
,`process` int(3)
,`value` int(32)
);

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%users`
--

CREATE TABLE `%dbprefix%users` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) DEFAULT NULL,
  `uname` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` int(3) DEFAULT NULL,
  `dept` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `%dbprefix%users`
--

INSERT INTO `%dbprefix%users` VALUES
('KkEtq2SNzvl02OR', 'admin', '', 'admin', 'pass', now(), 1, 1);

-- --------------------------------------------------------

--
-- Structure for view `%dbprefix%temp`
--
DROP TABLE IF EXISTS `%dbprefix%temp`;

CREATE VIEW `%dbprefix%temp`  AS  select `%dbprefix%cache`.`id` AS `id`,`%dbprefix%cache`.`fname` AS `fname`,`%dbprefix%cache`.`lname` AS `lname`,`%dbprefix%cache`.`time` AS `time`,`%dbprefix%cache`.`uname` AS `uname`,`%dbprefix%cache`.`group` AS `group`,`%dbprefix%cache`.`process` AS `process`,`%dbprefix%cache`.`value` AS `value` from `%dbprefix%cache` where (`%dbprefix%cache`.`time` > (unix_timestamp() - 5)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `%dbprefix%cache`
--
ALTER TABLE `%dbprefix%cache`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group` (`group`),
  ADD KEY `cache_ibfk_2` (`uname`);

--
-- Indexes for table `%dbprefix%department`
--
ALTER TABLE `%dbprefix%department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dept` (`dept`);

--
-- Indexes for table `%dbprefix%groups`
--
ALTER TABLE `%dbprefix%groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groupid` (`groupid`);

--
-- Indexes for table `%dbprefix%group_users`
--
ALTER TABLE `%dbprefix%group_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupid` (`grupid`),
  ADD KEY `users` (`users`);

--
-- Indexes for table `%dbprefix%guest`
--
ALTER TABLE `%dbprefix%guest`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `guest_id` (`guest_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `%dbprefix%msgs`
--
ALTER TABLE `%dbprefix%msgs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grp_id` (`grp_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `%dbprefix%pusers`
--
ALTER TABLE `%dbprefix%pusers`
  ADD UNIQUE KEY `uname` (`uname`),
  ADD UNIQUE KEY `secret` (`secret`),
  ADD KEY `depart` (`depart`);

--
-- Indexes for table `%dbprefix%role`
--
ALTER TABLE `%dbprefix%role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dept` (`dept`);

--
-- Indexes for table `%dbprefix%setting`
--
ALTER TABLE `%dbprefix%setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `%dbprefix%users`
--
ALTER TABLE `%dbprefix%users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uname` (`uname`),
  ADD KEY `role` (`role`),
  ADD KEY `dept` (`dept`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `%dbprefix%department`
--
ALTER TABLE `%dbprefix%department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `%dbprefix%group_users`
--
ALTER TABLE `%dbprefix%group_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--

ALTER TABLE `%dbprefix%cache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT for table `%dbprefix%msgs`
--
ALTER TABLE `%dbprefix%msgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `%dbprefix%role`
--
ALTER TABLE `%dbprefix%role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `%dbprefix%setting`
--
ALTER TABLE `%dbprefix%setting`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- Constraints for dumped tables
--
INSERT INTO `%dbprefix%role` VALUES
(2, 'user', 'user with few privileges');
--
INSERT INTO `%dbprefix%role` VALUES
(3, 'user', 'guest with no privileges');
--
-- Constraints for table `%dbprefix%cache`
--
ALTER TABLE `%dbprefix%cache`
  ADD CONSTRAINT `cache_ibfk_1` FOREIGN KEY (`group`) REFERENCES `%dbprefix%groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cache_ibfk_2` FOREIGN KEY (`uname`) REFERENCES `%dbprefix%users` (`uname`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `%dbprefix%group_users`
--
ALTER TABLE `%dbprefix%group_users`
  ADD CONSTRAINT `group_users_ibfk_2` FOREIGN KEY (`users`) REFERENCES `%dbprefix%users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_users_ibfk_3` FOREIGN KEY (`grupid`) REFERENCES `%dbprefix%groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `%dbprefix%guest`
--
ALTER TABLE `%dbprefix%guest`
  ADD CONSTRAINT `guest_ibfk_1` FOREIGN KEY (`id`) REFERENCES `%dbprefix%users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `guest_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `%dbprefix%groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `%dbprefix%msgs`
--
ALTER TABLE `%dbprefix%msgs`
  ADD CONSTRAINT `msgs_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `%dbprefix%users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `msgs_ibfk_3` FOREIGN KEY (`grp_id`) REFERENCES `%dbprefix%groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `%dbprefix%pusers`
--
ALTER TABLE `%dbprefix%pusers`
  ADD CONSTRAINT `pusers_ibfk_1` FOREIGN KEY (`depart`) REFERENCES `%dbprefix%department` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `%dbprefix%users`
--
ALTER TABLE `%dbprefix%users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`dept`) REFERENCES `%dbprefix%department` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`role`) REFERENCES `%dbprefix%role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

