<?php
/**
 * Abstract class for Action
 */


abstract class Rtfd_Abstract_Action {
    /**
     * @var Rtfd_Config
     */
    private $_config;

    /**
     * Rtfd_Abstract_Action constructor.
     * @param Rtfd_Config $config
     */
    final public function __construct(Rtfd_Config $config) {
        $this->_config = $config;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    final protected function get_option($key, $default = null) {
        return $this->_config->get_option($key, $default);
    }

    final protected function set_option($key, $value) {
        $this->_config->set_options($key, $value);
    }

    /**
     * @return Rtfd_Config
     */
    final protected function get_config() {
       return $this->_config;
    }

    /**
     * abstract method of Action start
     */
    abstract public function start();
}
