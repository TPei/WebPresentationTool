<?php
/**
 * @author Thomas Peikert
 */
include 'bootstrap.php';
SessionManager::instance()->destroySession();
header('location: expo.php');
