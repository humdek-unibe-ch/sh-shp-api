<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
spl_autoload_register(function ($class_name) {
    if (strpos($class_name, "Api") === 0) {
        $pluginDir = PLUGIN_SERVER_PATH;
        // Search for matching files in plugin directories
        $pluginPaths = glob($pluginDir . '/*/server/api/' . $class_name . '.php', GLOB_BRACE);
        foreach ($pluginPaths as $pluginPath) {
            if (file_exists($pluginPath)) {
                require_once $pluginPath;
                return;
            }
        }
    }
});

require_once __DIR__ . "/../../../../component/BaseModel.php";

/**
 * This class is used to prepare all API requests for Selfhelp
 */
class ApiRequest extends BaseModel
{

    /* Private Properties *****************************************************/

    /**
     * The user who made the request
     */
    private $id_users;

    /**
     * The response that will be send on the request
     */
    protected $response = array();


    /* Constructors ***********************************************************/

    /**
     * The constructor.
     *
     * @param array $services
     *  An associative array holding the different available services. See the
     *  class definition BasePage for a list of all services.
     */
    public function __construct($services)
    {
        parent::__construct($services);
        // Disable displaying errors
        ini_set('display_errors', DEBUG);
    }

    /* Private Methods *********************************************************/

    /**
     * Get the message based on the response code
     * @param int $code
     * The http code
     * @return string
     * Return the message corresponding to the code
     */
    private function get_response_code_message($code)
    {
        $text = "no message";
        if ($code !== NULL) {
            switch ($code) {
                case 100:
                    $text = 'Continue';
                    break;
                case 101:
                    $text = 'Switching Protocols';
                    break;
                case 200:
                    $text = 'OK';
                    break;
                case 201:
                    $text = 'Created';
                    break;
                case 202:
                    $text = 'Accepted';
                    break;
                case 203:
                    $text = 'Non-Authoritative Information';
                    break;
                case 204:
                    $text = 'No Content';
                    break;
                case 205:
                    $text = 'Reset Content';
                    break;
                case 206:
                    $text = 'Partial Content';
                    break;
                case 300:
                    $text = 'Multiple Choices';
                    break;
                case 301:
                    $text = 'Moved Permanently';
                    break;
                case 302:
                    $text = 'Moved Temporarily';
                    break;
                case 303:
                    $text = 'See Other';
                    break;
                case 304:
                    $text = 'Not Modified';
                    break;
                case 305:
                    $text = 'Use Proxy';
                    break;
                case 400:
                    $text = 'Bad Request';
                    break;
                case 401:
                    $text = 'Unauthorized';
                    break;
                case 402:
                    $text = 'Payment Required';
                    break;
                case 403:
                    $text = 'Forbidden';
                    break;
                case 404:
                    $text = 'Not Found';
                    break;
                case 405:
                    $text = 'Method Not Allowed';
                    break;
                case 406:
                    $text = 'Not Acceptable';
                    break;
                case 407:
                    $text = 'Proxy Authentication Required';
                    break;
                case 408:
                    $text = 'Request Time-out';
                    break;
                case 409:
                    $text = 'Conflict';
                    break;
                case 410:
                    $text = 'Gone';
                    break;
                case 411:
                    $text = 'Length Required';
                    break;
                case 412:
                    $text = 'Precondition Failed';
                    break;
                case 413:
                    $text = 'Request Entity Too Large';
                    break;
                case 414:
                    $text = 'Request-URI Too Large';
                    break;
                case 415:
                    $text = 'Unsupported Media Type';
                    break;
                case 500:
                    $text = 'Internal Server Error';
                    break;
                case 501:
                    $text = 'Not Implemented';
                    break;
                case 502:
                    $text = 'Bad Gateway';
                    break;
                case 503:
                    $text = 'Service Unavailable';
                    break;
                case 504:
                    $text = 'Gateway Time-out';
                    break;
                case 505:
                    $text = 'HTTP Version not supported';
                    break;
                default:
                    $text = 'Unknown http status code "' . htmlentities($code) . '"';
                    break;
            }
        }
        return $text;
    }

    /**
     * Get the requested mode based on the requested method
     * @return string
     * Return the mode
     */
    private function get_requested_mode()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $mode = SELECT;
                break;
            case 'POST':
                $mode = INSERT;
                break;
            case 'PUT':
                $mode = UPDATE;
                break;
            case 'PATCH':
                $mode = UPDATE;
                break;
            case 'DELETE':
                $mode = DELETE;
                break;
            default:
                $mode = 'unknown';
                break;
        }
        return $mode;
    }

    /**
     * Add a api log entry to the database
     *
     * @param object $return_response
     *  The response of the api request
     * @return int
     *  The id of the log entry
     */
    private function insert_api_log($return_response)
    {
        $callback_id = $this->db->insert("apiLogs", array(
            "id_users" => $_SESSION['id_user'],
            "remote_addr" => $_SERVER['REMOTE_ADDR'],
            "target_url" => $_SESSION['target_url'],
            "post_params" => json_encode($_POST),
            "status" => $return_response['status'],
            "return_response" => json_encode($return_response)
        ));
        return $callback_id;
    }

    /* Public Methods *********************************************************/

    /**
     * Return the json response
     */
    public function return_response()
    {
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        $this->insert_api_log($this->response);
        echo json_encode($this->response);
    }

    /**
     * Get user id by api token
     * @param string $api_key
     * The api key sent by the users
     * @return mixed
     * Return the user id or false
     */
    public function get_user_id_by_api_token($api_key)
    {
        $sql = "SELECT id_users FROM users_api  WHERE token = :api_key";
        $res = $this->db->query_db_first($sql, array(":api_key" => $api_key));
        if ($res && isset($res['id_users'])) {
            return $res['id_users'];
        } else {
            return false;
        }
    }

    /**
     * Check if the request is ok
     * @return boolean
     * Return true or false
     */
    public function is_request_ok()
    {
        return $this->response['status'] === HTTP_OK;
    }

    /**
     * Execute API request
     * @param string $class_name
     * The class name
     * @param string $method_name
     * The method name
     * @param object $response
     * The response
     * @param object $params
     * The extra params
     */
    public function execute_api_request($class_name, $method_name, $response, $params = array())
    {
        $error_response = null;
        if (class_exists($class_name)) {
            $instance = new $class_name($this->services, $response);
            if (!method_exists($instance, $method_name)) {
                $error_response = "Request '$class_name' has no method '$method_name'";
            } else {
                $reflection = new ReflectionMethod($instance, $method_name);
                if ($reflection->isPublic()) {
                    $parameters = $reflection->getParameters();
                    $passedParameters = array_keys($params);
                    $invalidParameters = array_diff($passedParameters, array_column($parameters, 'name'));
                    if (count($invalidParameters) > 0) {
                        $this->set_status(HTTP_NOT_ACCEPTABLE);
                        $this->set_error_message('Invalid parameters: ' . implode(', ', $invalidParameters));
                        $this->return_response();
                        return;
                    } else {
                        call_user_func_array(array($instance, $method_name), $params);
                    }
                } else {
                    $error_response = "Request '$class_name' method '$method_name'" . " is not public";
                }
            }
        } else {
            $error_response = "Unknown request '" . $class_name . "'";
        }
        if ($error_response) {
            $this->response["timestamp"] = date("Y-m-d H:i:s");
            $this->response['status'] = HTTP_NOT_FOUND;
            $this->response['message'] = $this->get_response_code_message(HTTP_NOT_FOUND);
            $this->return_response();
        }
    }

    /**
     * Authorize the user based on the X_API_KEY that was sent
     * @return array
     * Return the response
     */
    public function authorizeUser()
    {
        $headers = getallheaders();
        if (isset($headers[X_API_KEY])) {
            $requested_mode = $this->get_requested_mode();
            $api_key = $headers[X_API_KEY];
            $user_id = $this->get_user_id_by_api_token($api_key);
            if ($user_id > 0) {
                $page_id = $this->db->fetch_page_id_by_keyword($this->router->route['name']);
                $has_access = $this->acl->has_access($user_id, $page_id, $requested_mode);
                $_SESSION['id_user'] = $user_id;
            } else {
                $has_access = false;
            }
            if ($has_access) {
                return array(
                    "timestamp" => date("Y-m-d H:i:s"),
                    "status" => HTTP_OK,
                    "message" => $this->get_response_code_message(HTTP_OK)
                );
            } else {
                return array(
                    "timestamp" => date("Y-m-d H:i:s"),
                    "status" => HTTP_UNAUTHORIZED,
                    "message" => $this->get_response_code_message(HTTP_UNAUTHORIZED)
                );
            }
        } else {
            return array(
                "timestamp" => date("Y-m-d H:i:s"),
                "status" => HTTP_BAD_REQUEST,
                "message" => $this->get_response_code_message(HTTP_BAD_REQUEST)
            );
        }
    }

    /**
     * Init function for $response
     * @param object $response
     * The response message
     */
    public function init_response($response)
    {
        $this->response = $response;
    }

    /**
     * Getter function for response
     * @return object
     * the response object
     */
    public function get_response()
    {
        return $this->response['response'];
    }

    /**
     * Getter function for status
     * @return int
     * the status code
     */
    public function get_status()
    {
        return $this->response['status'];
    }

    /**
     * Getter function for message
     * @return string
     * the response object
     */
    public function get_message()
    {
        return $this->response['message'];
    }

    /**
     * Setter function for response
     * @param object $response
     */
    public function set_response($response)
    {
        $this->response['response'] = $response;
    }

    /**
     * Setter function for error message
     * @param object $error_message
     */
    public function set_error_message($error_message)
    {
        $this->response['error_message'] = $error_message;
    }

    /**
     * Setter function for status
     * @return int $status
     */
    public function set_status($status)
    {
        $this->response['status'] = $status;
        $this->response['message'] = $this->get_response_code_message($status);
    }

    /**
     * Setter function for message
     * @return string $message
     */
    public function set_message($message)
    {
        $this->response['message'] = $message;
    }
}
