<?php
/**
 * @author Thomas Peikert
 */
namespace handler;

use File;

abstract class AjaxHandler extends Handler {

    protected $ajaxData = array();

    protected function ajaxData($key) {
        return $this->ajaxData[$key];
    }

    public static function createFromRequest(array $ajaxData) {
        $handler = $ajaxData['handler'];

        /**
         * all inheriting classes must be of format
         * XYZHandler in namspeace handler and must extend AjaxHandler
         * handler paramter should be lowercase (convention)
         */
        $handlerClass = __NAMESPACE__ . '\\' . ucfirst($handler) . 'Handler';

        /** @var AjaxHandler $handler */
        $handler = new $handlerClass();
        $handler->ajaxData = $ajaxData;

        // action name must be lowercase
        $action = $ajaxData['action'] . 'Action';

        call_user_func_array(array($handler, $action), array());
    }

}