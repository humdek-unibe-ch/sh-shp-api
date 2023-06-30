<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
require_once __DIR__ . "/../../../../../../component/BaseController.php";
/**
 * The controller class of the FotrockrsUser component.
 */
class ApiTokenController extends BaseController
{
    /* Private Properties *****************************************************/


    /* Constructors ***********************************************************/

    /**
     * The constructor.
     *
     * @param object $model
     *  The model instance of the component.
     */
    public function __construct($model)
    {
        parent::__construct($model);
        $mode = isset($_POST['mode']) ? $_POST['mode'] : false;
        if ($mode == API_GENERATE_TOKEN) {
            if ($this->model->generate_api_token()) {
                $this->success = true;
                $this->success_msgs[] = "An API token was generated!";
            } else {
                $this->fail = true;
                $this->error_msgs[] = "Failed to generate an API token!";
            }
        } else if ($mode == API_REVOKE_TOKEN) {
            if ($this->model->revoke_api_token()) {
                $this->success = true;
                $this->success_msgs[] = "The API token was revoked successfully!";
            } else {
                $this->fail = true;
                $this->error_msgs[] = "Failed to revoke the API token!";
            }
        }
    }

    /* Private Methods *********************************************************/


    /* Public Methods *********************************************************/
}
?>
