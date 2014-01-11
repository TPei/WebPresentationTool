<?php
use db\ImageManager;

include 'bootstrap.php';
$id = ImageManager::saveUpload('image');
?>
<img src="getImage.php?id=<?php echo $id ?>"/>