<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
require_once __DIR__ . "/../../../../../component/BaseController.php";
/**
 * The controller class of the FotrockrsUser component.
 */
class ApiSettingsController extends BaseController
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
        if ($this->model->get_mode() == UPDATE && $this->model->has_access_update()) {
            if ($this->model->generate_api_token()) {
                $this->success = true;
                $this->success_msgs[] = "An API token was generated!";
            } else {
                $this->fail = true;
                $this->error_msgs[] = "Failed to generate an API token!";
            }
        } else if ($this->model->get_mode() == DELETE && $this->model->has_access_delete()) {
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
