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
     */
    public function get_external($table_name)
    {
        $id_table = $this->user_input->get_form_id($table_name, FORM_EXTERNAL);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $data = $this->user_input->get_data_for_user($id_table, $_SESSION['id_user'], '', FORM_EXTERNAL);
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
     */
    public function get_external_all($table_name)
    {
        $id_table = $this->user_input->get_form_id($table_name, FORM_EXTERNAL);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $data = $this->user_input->get_data($id_table, ' LIMIT 0, 10000', false, FORM_EXTERNAL);
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
     */
    public function get_internal($table_name)
    {
        $id_table = $this->user_input->get_form_id($table_name, FORM_INTERNAL);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $data = $this->user_input->get_data_for_user($id_table, $_SESSION['id_user'], '', FORM_INTERNAL);
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
        $id_table = $this->user_input->get_form_id($table_name, FORM_INTERNAL);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $data = $this->user_input->get_data($id_table, $filter, false, FORM_INTERNAL);
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
        $id_table = $this->user_input->get_form_id($table_name, FORM_EXTERNAL);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $res = $this->user_input->save_external_data(transactionBy_by_user, $table_name, $data);
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
        $id_table = $this->user_input->get_form_id($table_name, FORM_EXTERNAL);
        if (!$id_table) {
            $this->set_status(HTTP_NOT_FOUND);
            $this->set_error_message('The table does not exists!');
        } else {
            $res = $this->user_input->save_external_data(transactionBy_by_user, $table_name, $data);
            $this->set_response($res);
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
        $id_table = $this->user_input->get_form_id($table_name, FORM_EXTERNAL);
        if ($id_table) {
            $this->set_status(HTTP_CONFLICT);
            $this->set_error_message('The table already exists!');
        } else {
            $res = $this->db->insert("uploadTables", array(
                "name" => $table_name
            ));
            $this->set_response($res);
        }
        $this->return_response();
    }
}
