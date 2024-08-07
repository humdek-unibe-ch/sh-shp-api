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

    /**
     * Inserts a new data table.
     *
     * @param array $data The data to insert, including 'name' and 'displayName'.
     * @return mixed The response after attempting to insert the data table.
     */
    private function api_insert_dataTable($data)
    {
        if (!isset($data['name']) || !isset($data['displayName'])) {
            $this->set_status(HTTP_CONFLICT);
            $this->set_error_message('Name or displayName variable is not set!');
        } else {
            $id_table = $this->user_input->get_dataTable_id($data['name']);
            if ($id_table) {
                $this->set_status(HTTP_CONFLICT);
                $this->set_error_message('The table already exists!');
            } else {
                $res = $this->db->insert("dataTables", array(
                    "name" => $data['name'],
                    "displayName" => $data['displayName']
                ));
                $this->set_response($res);
            }
        }
        return $this->return_response();
    }

    /**
     * Inserts data into an existing data table.
     *
     * @param array $data The data to insert.
     * @param string $table_name The name of the table where the data will be inserted.
     * @return mixed The response after attempting to insert the data.
     */
    private function api_insert_data($data, $table_name)
    {
        $id_table = $this->user_input->get_dataTable_id($table_name);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $res = $this->user_input->save_data(transactionBy_by_user, $table_name, $data);
            $this->set_response($res);
        }
        return $this->return_response();
    }


    /* Public Methods *********************************************************/

    /**
     * Get the data for all users for the selected dataTable
     * GET protocol
     * URL: /api/data/table/table_name
     * @param string $table_name
     * The name of the external table
     * @param string $filter
     * It comes from the $_GET parameters
     * @param null | "my" $user_mode
     * If user mode is set to "my", it returns data only for the logged user
     * It is empty by default if it is not sent
     */
    public function GET_table($table_name, $filter = '', $user_mode = null)
    {
        $id_table = $this->user_input->get_dataTable_id($table_name);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $data = $this->user_input->get_data($id_table, $filter, $user_mode == 'my');
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
     * Handles POST requests for table-related operations.
     *
     * @param array $data The data to process.
     * @param string|null $table_name The name of the table (optional).
     * @return mixed The response after processing the request.
     */
    public function POST_table($data, $table_name = null)
    {
        if ($table_name) {
            // insert data in dataTable
            return $this->api_insert_data($data, $table_name);
        } else {
            // create a new dataTable
            return $this->api_insert_dataTable($data);
        }
    }

    /**
     * Update data in dataTable
     * PUT protocol
     * URL: /api/data/table/table_name/record_id
     * @param string $table_name
     * The name of the external table
     * @param string $record_id
     * The row id witch will be updated
     * @param object $data
     * The data object that we want to import. It is an associative array where each key is the name of the column     
     */
    public function PUT_table($table_name, $record_id, $data)
    {
        $id_table = $this->user_input->get_dataTable_id($table_name);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            if (!$record_id) {
                $this->set_status(HTTP_NOT_FOUND);
                $this->set_error_message('There is no data for which row should be updated!');
            } else {
                $update_row = [];
                $update_row['record_id'] = $record_id;
                $update_row['id_users'] = $_SESSION['id_user'];
                $res = $this->user_input->save_data(transactionBy_by_user, $table_name, $data, $update_row);
                $this->set_response($res);
            }
        }
        $this->return_response();
    }
}
