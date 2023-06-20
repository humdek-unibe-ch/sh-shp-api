<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
require_once __DIR__ . "/../../../../component/BaseHooks.php";
require_once __DIR__ . "/apiSettings/ApiSettingsComponent.php";

/**
 * The class to define the hooks for the plugin.
 */
class ApiHooks extends BaseHooks
{
    /* Constructors ***********************************************************/

    /**
     * The constructor creates an instance of the hooks.
     * @param object $services
     *  The service handler instance which holds all services
     * @param object $params
     *  Various params
     */
    public function __construct($services, $params = array())
    {
        parent::__construct($services, $params);
    }

    /* Private Methods *********************************************************/


    /* Public Methods *********************************************************/
    /**
     * Return a BaseStyleComponent object
     * @param object $args
     * Params passed to the method
     * @return object
     * Return a BaseStyleComponent object
     */
    public function outputShowApiToken()
    {
        $uid = $this->router->get_param_by_name('uid'); 
        $uid = $uid ? $uid : $_SESSION['id_user']; //if the user is not set then we use the session user id
        $apiComponent = new ApiSettingsComponent($this->services, array("uid" => $uid));
        $apiComponent->output_content();
    }
}
?>
