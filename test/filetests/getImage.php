<?php

use db\ImageManager;

include '../../bootstrap.php';

$id = $_GET['id'];
$id = new MongoId($id);

$image = ImageManager::getImage($id);

header("Content-type: $image->mime");

echo $image->fileData;