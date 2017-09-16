-- No Direct Access --<?php die; ?>

--
-- Database: `kchat_sql`
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
  `value` int(32) DEFAULT NULL,
  `dept` int(11) DEFAULT NULL,
  `support_id` varchar(32) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%department`
--

CREATE TABLE `%dbprefix%department` (
  `id` int(11) NOT NULL,
  `dept` varchar(20) DEFAULT NULL,
  `discription` varchar(64) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
  `email` varchar(64) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%notification`
--

CREATE TABLE `%dbprefix%notification` (
  `id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `url` varchar(64) NOT NULL,
  `notification` varchar(64) NOT NULL,
  `user` varchar(64) NOT NULL,
  `seen` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%plotly`
--

CREATE TABLE `%dbprefix%plotly` (
  `id` int(11) NOT NULL,
  `y` int(11) NOT NULL DEFAULT '0',
  `x` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%pusers`
--

CREATE TABLE `%dbprefix%pusers` (
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `uname` varchar(20) NOT NULL,
  `email` varchar(40) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `%dbprefix%users`
--

CREATE TABLE `%dbprefix%users` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) DEFAULT NULL,
  `email` varchar(40) NOT NULL,
  `uname` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `role` int(3) DEFAULT NULL,
  `dept` int(3) DEFAULT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure for view `%dbprefix%temp`
--
DROP TABLE IF EXISTS `%dbprefix%temp`;

CREATE VIEW `%dbprefix%temp` AS select `%dbprefix%cache`.`id` AS `id`,`%dbprefix%cache`.`fname` AS `fname`,`%dbprefix%cache`.`lname` AS `lname`,`%dbprefix%cache`.`time` AS `time`,`%dbprefix%cache`.`uname` AS `uname`,`%dbprefix%cache`.`group` AS `group`,`%dbprefix%cache`.`process` AS `process`,`%dbprefix%cache`.`value` AS `value`,`%dbprefix%cache`.`dept` AS `dept`,`%dbprefix%cache`.`support_id` AS `support_id` from `%dbprefix%cache` where (`%dbprefix%cache`.`time` > (unix_timestamp() - 5));

--
-- Indexes for dumped tables
--

--
-- Indexes for table `%dbprefix%cache`
--
ALTER TABLE `%dbprefix%cache`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uname` (`uname`,`process`), ADD KEY `group` (`group`), ADD KEY `cache_ibfk_2` (`uname`), ADD KEY `dept` (`dept`), ADD KEY `support_id` (`support_id`), ADD KEY `support_id_2` (`support_id`), ADD KEY `dept_2` (`dept`);

--
-- Indexes for table `%dbprefix%department`
--
ALTER TABLE `%dbprefix%department`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `dept` (`dept`);

--
-- Indexes for table `%dbprefix%groups`
--
ALTER TABLE `%dbprefix%groups`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `groupid` (`groupid`);

--
-- Indexes for table `%dbprefix%group_users`
--
ALTER TABLE `%dbprefix%group_users`
  ADD PRIMARY KEY (`id`), ADD KEY `grupid` (`grupid`), ADD KEY `users` (`users`);

--
-- Indexes for table `%dbprefix%guest`
--
ALTER TABLE `%dbprefix%guest`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `guest_id` (`guest_id`), ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `%dbprefix%msgs`
--
ALTER TABLE `%dbprefix%msgs`
  ADD PRIMARY KEY (`id`), ADD KEY `grp_id` (`grp_id`), ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `%dbprefix%notification`
--
ALTER TABLE `%dbprefix%notification`
  ADD PRIMARY KEY (`id`), ADD KEY `user` (`user`);

--
-- Indexes for table `%dbprefix%plotly`
--
ALTER TABLE `%dbprefix%plotly`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `x` (`x`);

--
-- Indexes for table `%dbprefix%pusers`
--
ALTER TABLE `%dbprefix%pusers`
  ADD UNIQUE KEY `uname` (`uname`), ADD UNIQUE KEY `secret` (`secret`), ADD KEY `depart` (`depart`);

--
-- Indexes for table `%dbprefix%role`
--
ALTER TABLE `%dbprefix%role`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `dept` (`dept`);

--
-- Indexes for table `%dbprefix%setting`
--
ALTER TABLE `%dbprefix%setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `%dbprefix%users`
--
ALTER TABLE `%dbprefix%users`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uname` (`uname`), ADD KEY `role` (`role`), ADD KEY `dept` (`dept`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `%dbprefix%cache`
--
ALTER TABLE `%dbprefix%cache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `%dbprefix%department`
--
ALTER TABLE `%dbprefix%department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `%dbprefix%group_users`
--
ALTER TABLE `%dbprefix%group_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `%dbprefix%msgs`
--
ALTER TABLE `%dbprefix%msgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `%dbprefix%notification`
--
ALTER TABLE `%dbprefix%notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `%dbprefix%plotly`
--
ALTER TABLE `%dbprefix%plotly`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `%dbprefix%role`
--
ALTER TABLE `%dbprefix%role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `%dbprefix%setting`
--
ALTER TABLE `%dbprefix%setting`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `%dbprefix%cache`
--
ALTER TABLE `%dbprefix%cache`
ADD CONSTRAINT `cache_ibfk_1` FOREIGN KEY (`group`) REFERENCES `%dbprefix%groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cache_ibfk_2` FOREIGN KEY (`uname`) REFERENCES `%dbprefix%users` (`uname`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `%dbprefix%cache_ibfk_1` FOREIGN KEY (`support_id`) REFERENCES `%dbprefix%users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `%dbprefix%cache_ibfk_2` FOREIGN KEY (`dept`) REFERENCES `%dbprefix%department` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `%dbprefix%notification`
--
ALTER TABLE `%dbprefix%notification`
ADD CONSTRAINT `%dbprefix%notification_ibfk_1` FOREIGN KEY (`user`) REFERENCES `%dbprefix%users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

