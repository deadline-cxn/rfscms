CREATE TABLE IF NOT EXISTS `anyterm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `connection_type` text NOT NULL,
  `local_port` text NOT NULL,
  `username` text NOT NULL,
  `ipaddress` text NOT NULL,
  `port` text NOT NULL,
  `command` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `resource_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` text NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `transport_method` text NOT NULL,
  `table_ref` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `topology` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` text NOT NULL,
  `name` text NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `network_device` int(11) NOT NULL,
  `connected_to` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `script_group_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `script_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `pod` int(11) NOT NULL,
  `scripts` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `scripts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `script_group` int(11) NOT NULL,
  `network_device` int(11) NOT NULL,
  `type` text NOT NULL,
  `file` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `network_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Web page database id',
  `info` text NOT NULL,
  `hostname` text NOT NULL,
  `ipaddress` text NOT NULL,
  `port` int(11) NOT NULL,
  `proxy_device` text NOT NULL,
  `dname` text NOT NULL,
  `dpass` text NOT NULL,
  `mac` text NOT NULL,
  `machw` text NOT NULL,
  `resource_type` int(11) NOT NULL,
  `location` text NOT NULL,
  `model` text NOT NULL,
  `serial_number` text NOT NULL,
  `operating_system` text NOT NULL,
  `services` text NOT NULL COMMENT 'csv ports',
  `status` text NOT NULL,
  `uptime` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `objectives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `criteria` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `pod_completion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` text NOT NULL,
  `pod` int(11) NOT NULL,
  `objective` int(11) NOT NULL,
  `criteria_completed` text NOT NULL,
  `completed` text NOT NULL,
  `date_completed` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `pods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course` text NOT NULL,
  `sequence` int(11) NOT NULL,
  `prerequisites` text NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `objectives` text NOT NULL,
  `topology` int(11) NOT NULL,
  `available` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `logic` text NOT NULL,
  `logic_x` text NOT NULL,
  `logic_y` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
