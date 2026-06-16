-- Run once against vivid_space_db to add the notifications table.
CREATE TABLE IF NOT EXISTS `notifications` (
  `id`         int(11)                          NOT NULL AUTO_INCREMENT,
  `user_id`    int(11)                          NOT NULL,
  `actor_id`   int(11)                          NOT NULL,
  `type`       enum('follow','like','comment')  NOT NULL,
  `entity_id`  int(11)                          DEFAULT NULL,
  `is_read`    tinyint(1)                       NOT NULL DEFAULT 0,
  `created_at` datetime                         DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id`  (`user_id`),
  KEY `actor_id` (`actor_id`),
  CONSTRAINT `notif_user_fk`  FOREIGN KEY (`user_id`)  REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notif_actor_fk` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
