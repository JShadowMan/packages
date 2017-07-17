<?php
/**
 * Config Module
 */


class Rtfd_Config {
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
        $privilege = Rtfd_Jwt::token_decode($privilege_string, _rtfd_default_jwt_key_);

        $this->_username = $privilege['username'];

        $role_name = ucfirst($privilege['role']);
        $role_class = join('_', array('Rtfd_Role', $role_name));
        if (!Rtfd_Utils::class_exists($role_class)) {
            throw new Rtfd_Exception_FatalError('privilege string invalid',
                'Rtfd:Config:set_privilege');
        }
        $this->_user_role = new $role_class();
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
