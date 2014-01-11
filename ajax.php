<?php
/**
 * @author Thomas Peikert
 */

use handler\AjaxHandler;
include 'bootstrap.php';

$ajaxData = json_decode($_POST['ajax'], true);
AjaxHandler::createFromRequest($ajaxData);