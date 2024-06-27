-- add plugin entry in the plugin table
UPDATE `plugins`
SET version = 'v1.1.0'
WHERE `name` = 'api';

DELETE FROM pages
WHERE keyword IN ('api_get_internal','api_get_internal_all','api_import_external_row');

UPDATE pages
SET keyword = 'api_get_data', url = '/api/[data:class]/[table:method]/[v:table_name]'
WHERE keyword = 'api_get_external_all';

UPDATE pages
SET keyword = 'api_get_my_data', url = '/api/[my:userMode]/[data:class]/[table:method]/[v:table_name]'
WHERE keyword = 'api_get_external';

UPDATE pages
SET keyword = 'api_add_data', url = '/api/[data:class]/[table:method]/[v:table_name]'
WHERE keyword = 'api_import_external';

UPDATE pages
SET keyword = 'api_update_data', url = '/api/[data:class]/[table:method]/[v:table_name]'
WHERE keyword = 'api_update_external_row';

UPDATE pages
SET keyword = 'api_create_dataTable', url = '/api/[data:class]/[table:method]'
WHERE keyword = 'api_create_external_table';

