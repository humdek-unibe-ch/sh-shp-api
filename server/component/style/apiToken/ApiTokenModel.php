<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
require_once __DIR__ . "/../../../../../../component/user/UserModel.php";
/**
 * This class is used to prepare all data related to the ApiToken component such
 * that the data can easily be displayed in the view of the component.
 */
class ApiTokenModel extends UserModel
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
    public function __construct($services, $params)
    {
        $uid = isset($params['uid']) ? $params['uid'] : null;
        parent::__construct($services, $uid);
    }

    /* Private Methods *********************************************************/

    /**
     * Fetch the user info from DB
     * @param int $id_users
     * Selfhelp user id
     * @return object
     * Return the  user object
     */
    private function fetch_api_user($id_users)
    {
        $sql = "SELECT u.*, api.token AS api_token, api.created_at, api.updated_at
                FROM view_users u
                LEFT JOIN users_api api ON (u.id = api.id_users)
                WHERE u.id = :id_users;";
        return $this->db->query_db_first($sql, array(":id_users" => $id_users));
    }

    /* Public Methods *********************************************************/

    /**
     * Get the user
     * @param int $id_users
     * Selfhelp user id
     * @return object
     * Return the info for the  user
     */
    public function get_api_user($id_users = null)
    {
        if ($id_users == null) {
            $id_users = $this->get_uid();
        }
        return $this->fetch_api_user($id_users);
    }

    /**
     * Generate API token
     * @return mixed
     * Return the api once generated, return false if not
     */
    public function generate_api_token()
    {
        $api_user = $this->get_api_user();
        // Generate a unique ID based on the current timestamp
        $token = uniqid('', true);
        // Remove the dot from the generated ID
        $token = str_replace('.', '', $token);
        // Generate 16 random bytes using a secure random number generator
        $randomBytes = random_bytes(16);
        // Convert the random bytes to a hexadecimal string
        $randomHex = bin2hex($randomBytes);
        // Append the random hexadecimal string to the unique ID
        $token .= $randomHex;
        if (isset($api_user['id'])) {
            if (isset($api_user['created_at'])) {
                // the record exist, just update with new token
                $res = $this->db->update_by_ids('users_api', array('token' => $token), array('id_users' => $api_user['id']));
                if ($res) {
                    return $token;
                } else {
                    return false;
                }
            } else {
                // insert new record
                $res = $this->db->insert("users_api", array(
                    "id_users" => $api_user['id'],
                    "token" => $token
                ));
                if ($res) {
                    return $token;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Revoke API token
     * @return boolean
     * Return the result of the operation
     */
    public function revoke_api_token()
    {
        $api_user = $this->get_api_user();
        if (isset($api_user['id'])) {
            $res = $this->db->update_by_ids('users_api', array('token' => null), array('id_users' => $api_user['id']));
            return $res;
        } else {
            return false;
        }
    }

    /**
     * Check if the user has access to select the API settings
     * @return boolean
     * Return true or false
     */
    public function has_access_select()
    {
        $page_id = $this->db->fetch_page_id_by_keyword(PAGE_API_SETTINGS);
        $res = $this->acl->has_access_select($_SESSION['id_user'], $page_id);
        return $res;
    }

    /**
     * Check if the user has access to update the API settings
     * @return boolean
     * Return true or false
     */
    public function has_access_update()
    {
        $page_id = $this->db->fetch_page_id_by_keyword(PAGE_API_SETTINGS);
        $res = $this->acl->has_access_update($_SESSION['id_user'], $page_id);
        return $res;
    }

    /**
     * Check if the user has access to delete the API settings
     * @return boolean
     * Return true or false
     */
    public function has_access_delete()
    {
        $page_id = $this->db->fetch_page_id_by_keyword(PAGE_API_SETTINGS);
        $res = $this->acl->has_access_delete($_SESSION['id_user'], $page_id);
        return $res;
    }
}
