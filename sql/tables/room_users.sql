CREATE TABLE `room_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `roomId` int(10) UNSIGNED NOT NULL
  `userId` int(10) UNSIGNED NOT NULL
  FOREIGN KEY (roomId) REFERENCES rooms(id)
  FOREIGN KEY (userId) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;