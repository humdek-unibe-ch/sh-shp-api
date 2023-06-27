-- add plugin entry in the plugin table
INSERT IGNORE INTO plugins (name, version) 
VALUES ('api', 'v1.0.0');

INSERT IGNORE INTO `groups` (`name`, `description`, `id_group_types`) VALUES ("API", 'Access to all API functionalities', (SELECT id FROM lookups WHERE type_code = 'groupTypes' AND lookup_code = 'db_role'));

-- add page generate_api_token
INSERT IGNORE INTO `pages` (`keyword`, `url`, `protocol`, `id_actions`, `id_navigation_section`, `parent`, `is_headless`, `nav_position`, `footer_position`, `id_type`, `id_pageAccessTypes`) 
VALUES ('apiSettings', '/api_settings/[v:mode]/[i:uid]?', 'GET|POST', (SELECT id FROM actions WHERE `name` = 'component' LIMIT 0,1), NULL, NULL, '0', NULL, NULL, (SELECT id FROM pageType WHERE `name` = 'intern' LIMIT 0,1), (SELECT id FROM lookups WHERE lookup_code = 'web'));
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

-- create table that keeps information about the requested APIs
CREATE TABLE IF NOT EXISTS `apiLogs` (
	`id` INTEGER PRIMARY KEY AUTO_INCREMENT,
	`timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	`id_users` INT(10) UNSIGNED ZEROFILL NOT NULL,
	`remote_addr` VARCHAR(200),
	`target_url` VARCHAR(1000),
	`post_params` LONGTEXT,
	`status` INTEGER, -- statuscode are defined in the globals.php 
	`return_response` longtext,
	CONSTRAINT `apiLogs_fk_id_users` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- register hook  for output API token in user profile
INSERT IGNORE INTO `hooks` (`id_hookTypes`, `name`, `description`, `class`, `function`, `exec_class`, `exec_function`) VALUES ((SELECT id FROM lookups WHERE lookup_code = 'hook_on_function_execute' LIMIT 0,1), 'api-show-token', 'Show API token in the profile. Add buttone for generate a new one if there are permissions', 'ProfileView', 'output_content', 'ApiHooks', 'outputShowApiToken');

-- add action `api` for api calls
INSERT IGNORE INTO `actions`(`name`) VALUES('api');

-- register hook  to start listening for API requests
INSERT IGNORE INTO `hooks` (`id_hookTypes`, `name`, `description`, `class`, `function`, `exec_class`, `exec_function`) VALUES ((SELECT id FROM lookups WHERE lookup_code = 'hook_overwrite_return' LIMIT 0,1), 'init-api', 'Start listening for API requests', 'Selfhelp', 'web_call', 'ApiHooks', 'listen_for_api_request');

INSERT IGNORE INTO lookups (type_code, lookup_code, lookup_value, lookup_description) values ('transactionBy', 'by_api', 'By API', 'The action was done by the API');

-- add page api_ping
SET @page_keyword = 'api_ping';
INSERT IGNORE INTO `pages` (`keyword`, `url`, `protocol`, `id_actions`, `id_navigation_section`, `parent`, `is_headless`, `nav_position`, `footer_position`, `id_type`, `id_pageAccessTypes`) 
VALUES (@page_keyword, '/api/[base:class]/[ping:method]', 'GET', (SELECT id FROM actions WHERE `name` = 'api' LIMIT 0,1), NULL, NULL, '0', NULL, NULL, (SELECT id FROM pageType WHERE `name` = 'intern' LIMIT 0,1), (SELECT id FROM lookups WHERE lookup_code = 'api'));
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000001', 'API Ping');
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000002', 'API Ping');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "admin"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "API"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');

-- add page api_hallo
SET @page_keyword = 'api_hallo';
INSERT IGNORE INTO `pages` (`keyword`, `url`, `protocol`, `id_actions`, `id_navigation_section`, `parent`, `is_headless`, `nav_position`, `footer_position`, `id_type`, `id_pageAccessTypes`) 
VALUES (@page_keyword, '/api/[base:class]/[hallo:method]/[v:param1]', 'GET', (SELECT id FROM actions WHERE `name` = 'api' LIMIT 0,1), NULL, NULL, '0', NULL, NULL, (SELECT id FROM pageType WHERE `name` = 'intern' LIMIT 0,1), (SELECT id FROM lookups WHERE lookup_code = 'api'));
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000001', 'API Hallo');
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000002', 'API Hallo');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "admin"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "API"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');

-- add page api_post_hallo
SET @page_keyword = 'api_hallo_post';
INSERT IGNORE INTO `pages` (`keyword`, `url`, `protocol`, `id_actions`, `id_navigation_section`, `parent`, `is_headless`, `nav_position`, `footer_position`, `id_type`, `id_pageAccessTypes`) 
VALUES (@page_keyword, '/api/[base:class]/[hallo_post:method]/[v:name]', 'POST', (SELECT id FROM actions WHERE `name` = 'api' LIMIT 0,1), NULL, NULL, '0', NULL, NULL, (SELECT id FROM pageType WHERE `name` = 'intern' LIMIT 0,1), (SELECT id FROM lookups WHERE lookup_code = 'api'));
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000001', 'API Hallo Post');
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000002', 'API Hallo Post');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "admin"), (SELECT id FROM pages WHERE keyword = @page_keyword), '0', '1', '0', '0');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "API"), (SELECT id FROM pages WHERE keyword = @page_keyword), '0', '1', '0', '0');

-- add page api_get_external
SET @page_keyword = 'api_get_external';
INSERT IGNORE INTO `pages` (`keyword`, `url`, `protocol`, `id_actions`, `id_navigation_section`, `parent`, `is_headless`, `nav_position`, `footer_position`, `id_type`, `id_pageAccessTypes`) 
VALUES (@page_keyword, '/api/[data:class]/[get_external:method]/[v:table_name]', 'GET', (SELECT id FROM actions WHERE `name` = 'api' LIMIT 0,1), NULL, NULL, '0', NULL, NULL, (SELECT id FROM pageType WHERE `name` = 'intern' LIMIT 0,1), (SELECT id FROM lookups WHERE lookup_code = 'api'));
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000001', 'Get External Data');
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000002', 'Get External Data');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "admin"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "API"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');

-- add page api_get_internal
SET @page_keyword = 'api_get_internal';
INSERT IGNORE INTO `pages` (`keyword`, `url`, `protocol`, `id_actions`, `id_navigation_section`, `parent`, `is_headless`, `nav_position`, `footer_position`, `id_type`, `id_pageAccessTypes`) 
VALUES (@page_keyword, '/api/[data:class]/[get_internal:method]/[v:table_name]', 'GET', (SELECT id FROM actions WHERE `name` = 'api' LIMIT 0,1), NULL, NULL, '0', NULL, NULL, (SELECT id FROM pageType WHERE `name` = 'intern' LIMIT 0,1), (SELECT id FROM lookups WHERE lookup_code = 'api'));
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000001', 'Get Internal Data');
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000002', 'Get Internal Data');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "admin"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "API"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');

-- add page api_get_external
SET @page_keyword = 'api_get_external_all';
INSERT IGNORE INTO `pages` (`keyword`, `url`, `protocol`, `id_actions`, `id_navigation_section`, `parent`, `is_headless`, `nav_position`, `footer_position`, `id_type`, `id_pageAccessTypes`) 
VALUES (@page_keyword, '/api/[data:class]/[get_external_all:method]/[v:table_name]', 'GET', (SELECT id FROM actions WHERE `name` = 'api' LIMIT 0,1), NULL, NULL, '0', NULL, NULL, (SELECT id FROM pageType WHERE `name` = 'intern' LIMIT 0,1), (SELECT id FROM lookups WHERE lookup_code = 'api'));
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000001', 'Get External Data All');
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000002', 'Get External Data All');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "admin"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "API"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');

-- add page api_get_internal_all
SET @page_keyword = 'api_get_internal_all';
INSERT IGNORE INTO `pages` (`keyword`, `url`, `protocol`, `id_actions`, `id_navigation_section`, `parent`, `is_headless`, `nav_position`, `footer_position`, `id_type`, `id_pageAccessTypes`) 
VALUES (@page_keyword, '/api/[data:class]/[get_internal_all:method]/[v:table_name]', 'GET', (SELECT id FROM actions WHERE `name` = 'api' LIMIT 0,1), NULL, NULL, '0', NULL, NULL, (SELECT id FROM pageType WHERE `name` = 'intern' LIMIT 0,1), (SELECT id FROM lookups WHERE lookup_code = 'api'));
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000001', 'Get Internal Data All');
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000002', 'Get Internal Data All');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "admin"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "API"), (SELECT id FROM pages WHERE keyword = @page_keyword), '1', '0', '0', '0');

-- add page api_import_external_row
SET @page_keyword = 'api_import_external_row';
INSERT IGNORE INTO `pages` (`keyword`, `url`, `protocol`, `id_actions`, `id_navigation_section`, `parent`, `is_headless`, `nav_position`, `footer_position`, `id_type`, `id_pageAccessTypes`) 
VALUES (@page_keyword, '/api/[data:class]/[import_external_row:method]/[v:table_name]', 'POST', (SELECT id FROM actions WHERE `name` = 'api' LIMIT 0,1), NULL, NULL, '0', NULL, NULL, (SELECT id FROM pageType WHERE `name` = 'intern' LIMIT 0,1), (SELECT id FROM lookups WHERE lookup_code = 'api'));
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000001', 'Import External Row');
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000002', 'Import External Row');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "admin"), (SELECT id FROM pages WHERE keyword = @page_keyword), '0', '1', '0', '0');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "API"), (SELECT id FROM pages WHERE keyword = @page_keyword), '0', '1', '0', '0');

-- add page api_import_external
SET @page_keyword = 'api_import_external';
INSERT IGNORE INTO `pages` (`keyword`, `url`, `protocol`, `id_actions`, `id_navigation_section`, `parent`, `is_headless`, `nav_position`, `footer_position`, `id_type`, `id_pageAccessTypes`) 
VALUES (@page_keyword, '/api/[data:class]/[import_external:method]/[v:table_name]', 'POST', (SELECT id FROM actions WHERE `name` = 'api' LIMIT 0,1), NULL, NULL, '0', NULL, NULL, (SELECT id FROM pageType WHERE `name` = 'intern' LIMIT 0,1), (SELECT id FROM lookups WHERE lookup_code = 'api'));
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000001', 'Import External Data');
INSERT IGNORE INTO `pages_fields_translation` (`id_pages`, `id_fields`, `id_languages`, `content`) VALUES ((SELECT id FROM pages WHERE keyword = @page_keyword), get_field_id('title'), '0000000002', 'Import External Data');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "admin"), (SELECT id FROM pages WHERE keyword = @page_keyword), '0', '1', '0', '0');
INSERT IGNORE INTO `acl_groups` (`id_groups`, `id_pages`, `acl_select`, `acl_insert`, `acl_update`, `acl_delete`) VALUES ((SELECT id FROM groups WHERE `name` = "API"), (SELECT id FROM pages WHERE keyword = @page_keyword), '0', '1', '0', '0');