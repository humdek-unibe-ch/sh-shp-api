-- add plugin entry in the plugin table
UPDATE `plugins`
SET version = 'v1.1.0'
WHERE `name` = 'api';

DELETE FROM pages
WHERE keyword IN ('api_get_internal','api_get_internal_all','api_import_external_row','api_hallo_post');

UPDATE pages
SET keyword = 'api_select_data', url = '/api/[data:class]/[table:method]/[:table_name]'
WHERE keyword = 'api_get_external_all';

UPDATE pages
SET keyword = 'api_select_my_data', url = '/api/[my:user_mode]/[data:class]/[table:method]/[:table_name]'
WHERE keyword = 'api_get_external';

UPDATE pages
SET keyword = 'api_insert_data', url = '/api/[data:class]/[table:method]/[:table_name]'
WHERE keyword = 'api_import_external';

UPDATE pages
SET keyword = 'api_update_data', url = '/api/[data:class]/[table:method]/[:table_name]'
WHERE keyword = 'api_update_external_row';

UPDATE pages
SET keyword = 'api_insert_dataTable', url = '/api/[data:class]/[table:method]'
WHERE keyword = 'api_create_external_table';

UPDATE pages
SET url = '/api/[base:class]/[hallo:method]/[v:name]', protocol = 'GET|POST'
WHERE keyword = 'api_hallo';

UPDATE acl_groups acl
INNER JOIN pages p ON acl.id_pages = p.id
INNER JOIN `groups` g ON acl.id_groups = g.id
SET acl.acl_insert = 1  -- or any other value you wish to set
WHERE p.keyword = 'api_hallo' AND g.`name` IN ('admin', 'API');
