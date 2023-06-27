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
        $this->init_response($response);
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
        $this->set_response("pong");
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
        $this->set_response("Hallo " . $param1);
        $this->return_response();
    }

    /**
     * Hallo post function
     * POST protocol
     * URL: /api/base/hallo_post/name
     * POST: my_name
     * @param string $name
     * The name for greetings
     * @param string $data
     * The post data
     */
    public function hallo_post($name, $data)
    {
        if (isset($data['my_name'])) {
            $this->set_response("Hallo " . $name . ". My name is: " . $data['my_name']);
        } else {
            $this->set_status(HTTP_NOT_FOUND);
        }
        $this->return_response();
    }
}
