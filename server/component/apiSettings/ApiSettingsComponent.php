<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
require_once __DIR__ . "/../../../../../component/BaseComponent.php";
require_once __DIR__ . "/ApiSettingsController.php";
require_once __DIR__ . "/ApiSettingsModel.php";
require_once __DIR__ . "/ApiSettingsView.php";

/**
 * The user update component.
 */
class ApiSettingsComponent extends BaseComponent
{
    /* Private Properties *****************************************************/

    /* Constructors ***********************************************************/

    /**
     * The constructor creates an instance of the Model class and the View
     * class and passes them to the constructor of the parent class.
     *
     * @param object $services
     *  An associative array holding the different available services. See the
     *  class definition BasePage for a list of all services.
     */
    public function __construct($services, $params)
    {
        $model = new ApiSettingsModel($services, $params);
        $param_uid = $services->get_router()->get_param_by_name('uid');
        if (!$param_uid) {
            // for normal users we cannot control their api tokens
            $controller = new ApiSettingsController($model);
            $view = new ApiSettingsView($model, $controller, $params);
            parent::__construct($model, $view, $controller);
        } else {
            $user = $model->get_api_user();
            if (isset($user['user_type_code']) &&  $user['user_type_code'] != userTypes_user) {
                // for normal users we cannot control their api tokens
                $controller = new ApiSettingsController($model);
                $view = new ApiSettingsView($model, $controller, $params);
                parent::__construct($model, $view, $controller);
            }
        }
    }
}
?>
