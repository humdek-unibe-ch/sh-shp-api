<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
require_once __DIR__ . "/../../../../../../component/BaseComponent.php";
require_once __DIR__ . "/ApiTokenController.php";
require_once __DIR__ . "/ApiTokenModel.php";
require_once __DIR__ . "/ApiTokenView.php";

/**
 * The user update component.
 */
class ApiTokenComponent extends BaseComponent
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
        $model = new ApiTokenModel($services, $params);
        $controller = new ApiTokenController($model);
        $view = new ApiTokenView($model, $controller);
        parent::__construct($model, $view, $controller);
    }
}
?>
