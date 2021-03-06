<?php
/**
 * Config Module
 */


class Rtfd_Config {
    /**
     * @var string
     */
    private $_user_id;

    /**
     * @var string
     */
    private $_group_id;

    /**
     * @var string
     */
    private $_username;

    /**
     * @var Rtfd_Abstract_Role
     */
    private $_user_role;

    /**
     * @var string
     */
    private $_user_token;

    /**
     * @var array
     */
    private $_options;

    /**
     * Rtfd_Config constructor.
     * @param array $options
     */
    public function __construct(array $options) {
        $this->_options = $options;
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    public function get_option($name, $default = null) {
        if (array_key_exists($name, $this->_options)) {
            return $this->_options[$name];
        }
        return $default;
    }

    public function set_options($name, $value) {
        return $this->_options[$name] = $value;
    }

    /**
     * @return string
     */
    public function get_user_id() {
        return $this->_user_id;
    }

    /**
     * @return string
     */
    public function get_group_id() {
        return $this->_group_id;
    }

    /**
     * @return string
     */
    public function get_username() {
        return $this->_username;
    }

    /**
     * @return string
     */
    public function get_role_name() {
        return $this->_user_role->get_role_name();
    }

    /**
     * parse privilege string
     *
     * @param string $privilege_string
     * @throws Rtfd_Exception_FatalError
     */
    public function set_privilege($privilege_string) {
        $user_info = Rtfd_Jwt::token_decode($privilege_string, _rtfd_default_jwt_key_);
        // verify user
        $helper = new Rtfd_Helper_Database();
        $user_password = $helper->fetch_single_row(
            "select `password` from `users` where `uid`='{$user_info['uid']}';"
        );
        // check is valid
        if (md5($user_password['password']) !== $user_info['verify']) {
            $user_info = array(
                'uid' => 0,
                'gid' => 0,
                'username' => 'Guest',
                'expired' => 0,
                'role' => 'Guest'
            );
        }
        // user id
        $this->_user_id = $user_info['uid'];
        // group id
        $this->_group_id = $user_info['gid'];
        // username
        $this->_username = $user_info['username'];
        // initializing role
        $this->_user_role = new Rtfd_Role();
        $this->_user_role->set_role_name(ucfirst($user_info['role']));
        // update user token
        $this->_user_token = $privilege_string;
    }

    /**
     * @return int
     */
    public function get_privilege_level() {
        return $this->_user_role->get_privilege_level();
    }

    /**
     * @return array
     */
    public function get_allowed_actions() {
        return $this->_user_role->get_allowed_actions();
    }

    /**
     * check current action is allowed
     *
     * @param string $action
     */
    public function action_allowed($action) {
        if (!in_array(strtolower($action), $this->_user_role->get_allowed_actions(true))) {
            Rtfd_Request::abort(403, array(
                'errno' => 403,
                'error' => 'operator forbidden'
            ));
        }
    }

    /**
     * @return string
     */
    public function get_user_token() {
        return $this->_user_token;
    }
}
