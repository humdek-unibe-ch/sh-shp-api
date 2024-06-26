-- add plugin entry in the plugin table
UPDATE `plugins`
SET version = 'v1.1.0'
WHERE `name` = 'api';

DELETE FROM pages
WHERE keyword IN ('api_get_internal','api_get_internal_all');