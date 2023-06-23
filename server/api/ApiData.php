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
        } else {
            $data = $this->user_input->get_data_for_user($id_table, $_SESSION['id_user'], '', FORM_EXTERNAL);
            $this->set_response($data);
        }
        $this->return_response();
    }
}
