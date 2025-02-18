# v1.1.1
#### Fixed
- Resolved issue where `X-API-Key` header was not correctly retrieved due to case sensitivity in Apache and PHP.
- Updated `headers.php` to normalize headers using `array_change_key_case(getallheaders(), CASE_LOWER)`, ensuring case-insensitive access to request headers.

#### Impact
- API authentication now correctly recognizes `X-API-Key` regardless of capitalization.

# v1.1.0 - Requires SelfHelp v7.0.0+
### New Features
 - add computability with `user_input` refactoring from SelfHelp v7.0.0 
 - remove API calls:
   - `api_get_internal`
   - `api_get_internal_all`
   - `api_import_external_row`
   - `api_hallo_post`
 - rename APIs
   - `api_get_external_all` to `api_select_data` with url: `/api/[data:class]/[table:method]/[:table_name]`
   - `api_get_external` to `api_select_my_data` with url: `/api/[my:user_mode]/[data:class]/[table:method]/[:table_name]`
   - `api_create_external_table` to `api_insert_dataTable` with url: `/api/[data:class]/[table:method]`
   - `api_import_external` and `api_import_external_row` with `api_insert_data` with url: `/api/[data:class]/[table:method]/[:table_name]`
   - `api_update_external_row` to `api_update_data` with url: `/api/[data:class]/[table:method]/[:table_name]/[i:record_id]`

# v1.0.2
### New Features
 - load plugin version using `BaseHook` class 

# v1.0.1
### New Features
 - for external data, check if there is a field `_json` and convert the string to JSON object. This is for better SurveyJS data fetch

# v1.0.0

### New Features

 - The API plugin
