-- add plugin entry in the plugin table
INSERT IGNORE INTO plugins (name, version) 
VALUES ('api', 'v1.0.0');

INSERT IGNORE INTO `groups` (`name`, `description`, `id_group_types`) VALUES ("API", 'Access to all API functionalities', (SELECT id FROM lookups WHERE type_code = 'groupTypes' AND lookup_code = 'db_role'));

-- add page generate_api_token
INSERT IGNORE INTO `pages` (`id`, `keyword`, `url`, `protocol`, `id_actions`, `id_navigation_section`, `parent`, `is_headless`, `nav_position`, `footer_position`, `id_type`, `id_pageAccessTypes`) 
VALUES (NULL, 'apiSettings', '/api_settings/[v:mode]/[i:uid]?', 'GET|POST', (SELECT id FROM actions WHERE `name` = 'component' LIMIT 0,1), NULL, NULL, '0', NULL, NULL, (SELECT id FROM pageType WHERE `name` = 'intern' LIMIT 0,1), (SELECT id FROM lookups WHERE lookup_code = 'web'));
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = 'apiSettings'), get_field_id('title'), '0000000001', 'API Settings');
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = 'apiSettings'), get_field_id('title'), '0000000002', 'API Settings');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "admin"), (SELECT id FROM pages WHERE keyword = 'apiSettings'), '1', '1', '1', '1');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "API"), (SELECT id FROM pages WHERE keyword = 'apiSettings'), '1', '1', '1', '1');

-- create table users_api
CREATE TABLE IF NOT EXISTS `users_api` (
	`id_users` INT(10) UNSIGNED ZEROFILL NOT NULL PRIMARY KEY,		
	`token` VARCHAR(100) UNIQUE,    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `users_api_fk_id_users` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- register hook  for output API token in user profile
INSERT IGNORE INTO `hooks` (`id_hookTypes`, `name`, `description`, `class`, `function`, `exec_class`, `exec_function`) VALUES ((SELECT id FROM lookups WHERE lookup_code = 'hook_on_function_execute' LIMIT 0,1), 'api-show-token', 'Show API token in the profile. Add buttone for generate a new one if there are permissions', 'ProfileView', 'output_content', 'ApiHooks', 'outputShowApiToken');