CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;