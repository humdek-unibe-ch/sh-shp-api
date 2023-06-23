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
class ApiBase extends ApiRequest
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
        $this->set_response($response);
    }

    /* Private Methods *********************************************************/


    /* Public Methods *********************************************************/

    /**
     * Ping pong function
     * GET protocol
     * URL: /api/base/ping
     */
    public function ping()
    {
        $this->response['response'] = "pong";
        $this->return_response();
    }

    /**
     * Hallo function
     * GET protocol
     * URL: /api/base/hallo/param1
     * @param string $param1
     * The name for greetings
     */
    public function hallo($param1)
    {
        $this->response['response'] = "Hallo " . $param1;
        $this->return_response();
    }

    /**
     * Hallo post function
     * POST protocol
     * URL: /api/base/hallo_post/name
     * POST: my_name
     * @param string $name
     * The name for greetings
     * @param string $my_name
     * my name return, it comes form a post body
     */
    public function hallo_post($name, $my_name)
    {
        $this->response['response'] = "Hallo " . $name . ". My name is: ". $my_name;
        $this->return_response();
    }
}
