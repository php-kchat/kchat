-- No Direct Access --<?php die; ?>

--
-- Database: `kchat_sql`
--

--
-- Dumping data for table `%dbprefix%department`
--

INSERT INTO `%dbprefix%department` VALUES
(1, 'Admin', 'Head of Department'),
(2, 'support', 'support people'),
(3, 'IT', 'IT');

--
-- Dumping data for table `%dbprefix%groups`
--

INSERT INTO `%dbprefix%groups` VALUES
('NO_GROUP', '00000000000000000000000000000000', 'Undefined', now());

--
-- Dumping data for table `%dbprefix%plotly`
--

INSERT INTO `%dbprefix%plotly` VALUES
(1, 0, now());

--
-- Dumping data for table `%dbprefix%role`
--

INSERT INTO `%dbprefix%role` VALUES
(1, 'admin', 'user with all privileges'),
(2, 'user', 'user with few privileges'),
(3, 'guest', 'user with No privileges');

--
-- Dumping data for table `%dbprefix%setting`
--

INSERT INTO `%dbprefix%setting` VALUES
(1, 'headcolor1', '91EBE0', 'Head Color', 'Color Setting', 'color', 'background-color', '#KChat_heading,#kchat_copy'),
(2, 'bordercolor1', 'FFFFFF', 'body Color', 'Color Setting', 'color', 'background-color', '#KChat_scroll_panel'),
(3, 'mesgboxcolor1', 'FFFFFF', 'Message Box Color', 'Color Setting', 'color', 'background-color', '#KChat_textarea'),
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
(17, 'inboxbdrcx', 'FF0000', 'Left anchor Color', 'Color Setting', 'color', 'color', '.message0 a'),
(18, 'inboxback', 'FFFFFF', 'Message Box Backgrou', 'Color Setting', 'color', 'background-color', '#KChat_box');

--
-- Dumping data for table `%dbprefix%users`
--

INSERT INTO `%dbprefix%users` VALUES
('KkEtq2SNzvl02OR', 'admin', '', 'admin@mydomain.com', 'admin', 'pass', 1, 1, now());

--
-- Dumping data for table `%dbprefix%notification`
--

INSERT INTO `%dbprefix%notification` VALUES
(1, now(), '#', 'You Just Installed KChat', 'KkEtq2SNzvl02OR', 0);

