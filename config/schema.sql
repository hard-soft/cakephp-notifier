CREATE TABLE `profile_notifications` (
 `id` char(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `type` tinyint DEFAULT NULL,
 `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
 `status` tinyint DEFAULT '0',
 `due` datetime DEFAULT NULL,
 `createdby` char(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `created` datetime DEFAULT NULL,
 `modified` datetime NOT NULL,
 PRIMARY KEY (`id`),
 KEY `status` (`status`),
 KEY `due` (`due`),
 KEY `createdby` (`createdby`),
 KEY `created` (`created`),
 KEY `modified` (`modified`),
 KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `profile_notification_users` (
 `id` char(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `profile_notification_id` char(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `user_id` char(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `status` tinyint DEFAULT '0',
 `created` datetime DEFAULT NULL,
 `modified` datetime DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `profile_notification_id` (`profile_notification_id`),
 KEY `user_id` (`user_id`),
 KEY `status` (`status`),
 KEY `created` (`created`),
 KEY `modified` (`modified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `profile_notification_entities` (
 `id` char(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `profile_notification_id` char(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `entity_type` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `entity_id` char(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `created` datetime DEFAULT NULL,
 `modified` datetime DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `profile_notification_id` (`profile_notification_id`),
 KEY `entity_type` (`entity_type`),
 KEY `entity_id` (`entity_id`),
 KEY `created` (`created`),
 KEY `modified` (`modified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;