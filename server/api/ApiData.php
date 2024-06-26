<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
require_once __DIR__ . "/ApiRequest.php";

/**
 * This class is used to prepare all API requests for Selfhelp
 */
class ApiData extends ApiRequest
{

    /* Private Properties *****************************************************/


    /* Constructors ***********************************************************/

    /**
     * The constructor.
     *
     * @param array $services
     *  An associative array holding the different available services. See the
     *  class definition BasePage for a list of all services.
     */
    public function __construct($services, $response)
    {
        parent::__construct($services);
        $this->init_response($response);
    }

    /* Private Methods *********************************************************/


    /* Public Methods *********************************************************/

    /**
     * Get the data for the current user for the selected external table
     * GET protocol
     * URL: /api/data/get_external/table_name
     * @param string $table_name
     * The name of the external table
     * @param string $filter
     * It comes from the $_GET parameters
     * It is empty by default if it is not sent
     */
    public function get_external($table_name, $filter = '')
    {
        $id_table = $this->user_input->get_dataTable_id_by_displayName($table_name);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $data = $this->user_input->get_data_for_user($id_table, $_SESSION['id_user'], $filter);
            foreach ($data as $key => $value) {
                if (isset($value['_json'])) {
                    // if there is property _json, t is from survey js, make it json for easier work
                    $data[$key]['_json'] =  json_decode($value['_json'], true);
                } else {
                    // if there is no _json property break the loop, no point to loop all data
                    break;
                }
            }
            $this->set_response($data);
        }
        $this->return_response();
    }

    /**
     * Get the data for all users for the selected external table
     * GET protocol
     * URL: /api/data/get_external_all/table_name
     * @param string $table_name
     * The name of the external table
     * @param string $filter
     * It comes from the $_GET parameters
     * It is empty by default if it is not sent
     */
    public function get_external_all($table_name, $filter = '')
    {
        $id_table = $this->user_input->get_dataTable_id_by_displayName($table_name);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $data = $this->user_input->get_data($id_table, $filter, false);
            foreach ($data as $key => $value) {
                if (isset($value['_json'])) {
                    // if there is property _json, t is from survey js, make it json for easier work
                    $data[$key]['_json'] =  json_decode($value['_json'], true);
                } else {
                    // if there is no _json property break the loop, no point to loop all data
                    break;
                }
            }
            $this->set_response($data);
        }
        $this->return_response();
    }

    /**
     * Get the data for the current user for the selected internal table
     * GET protocol
     * URL: /api/data/get_internal/table_name
     * @param string $table_name
     * The name of the internal table
     * @param string $filter
     * It comes from the $_GET parameters
     * It is empty by default if it is not sent
     */
    public function get_internal($table_name, $filter = '')
    {
        $id_table = $this->user_input->get_dataTable_id_by_displayName($table_name);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $data = $this->user_input->get_data_for_user($id_table, $_SESSION['id_user'], $filter);
            $this->set_response($data);
        }
        $this->return_response();
    }

    /**
     * Get the data for all users for the selected internal table
     * GET protocol
     * URL: /api/data/get_internal_all/table_name
     * @param string $table_name
     * The name of the internal table
     * @param string $filter
     * It comes from the $_GET parameters
     * It is empty by default if it is not sent
     */
    public function get_internal_all($table_name, $filter = '')
    {
        $id_table = $this->user_input->get_dataTable_id_by_displayName($table_name);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $data = $this->user_input->get_data($id_table, $filter, false);
            $this->set_response($data);
        }
        $this->return_response();
    }

    /**
     * Import external data
     * POST protocol
     * URL: /api/data/import_external/table_name
     * @param string $table_name
     * The name of the external table
     * @param object $data
     * The data object that we want to import. It is an array where each entry is the row that we will import
     */
    public function import_external($table_name, $data)
    {
        $id_table = $this->user_input->get_dataTable_id_by_displayName($table_name);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $res = $this->user_input->save_data(transactionBy_by_user, $table_name, $data);
            $this->set_response($res);
        }
        $this->return_response();
    }

    /**
     * Import a row in external data
     * POST protocol
     * URL: /api/data/import_external_row/table_name
     * @param string $table_name
     * The name of the external table
     * @param object $data
     * The data object that we want to import. It is an associative array where each key is the name of the column
     */
    public function import_external_row($table_name, $data)
    {
        $id_table = $this->user_input->get_dataTable_id_by_displayName($table_name);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $res = $this->user_input->save_data(transactionBy_by_user, $table_name, $data);
            $this->set_response($res);
        }
        $this->return_response();
    }

    /**
     * Update a row in external data
     * PUT protocol
     * URL: /api/data/update_external_row/table_name
     * @param string $table_name
     * The name of the external table
     * @param string $row_id
     * The row id witch will be updated
     * @param object $data
     * The data object that we want to import. It is an associative array where each key is the name of the column     
     */
    public function update_external_row($table_name, $row_id, $data)
    {
        $id_table = $this->user_input->get_dataTable_id_by_displayName($table_name);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            if (!$row_id) {
                $this->set_status(HTTP_NOT_FOUND);
                $this->set_error_message('There is no data for which row should be updated!');
            } else {
                $update_row = [];
                $update_row['record_id'] = $row_id;
                $update_row['id_users'] = $_SESSION['id_user'];
                $res = $this->user_input->save_data(transactionBy_by_user, $table_name, $data, $update_row);
                $this->set_response($res);
            }
        }
        $this->return_response();
    }

    /**
     * Create external table
     * POST protocol
     * URL: /api/data/create_external_table/table_name
     * @param string $table_name
     * The name of the external table that we want to create
     */
    public function create_external_table($table_name)
    {
        $id_table = $this->user_input->get_dataTable_id_by_displayName($table_name);
        if ($id_table) {
            $this->set_status(HTTP_CONFLICT);
            $this->set_error_message('The table already exists!');
        } else {
            $res = $this->db->insert("dataTables", array(
                "name" => $table_name
            ));
            $this->set_response($res);
        }
        $this->return_response();
    }
}
