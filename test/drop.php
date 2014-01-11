<?php
use db\MongoAdapter;

/**
 * @author Thomas Peikert
 */
include '../bootstrap.php';
MongoAdapter::instance()->dropDatabase();