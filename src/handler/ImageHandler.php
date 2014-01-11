<?php
/**
 * @author Thomas Peikert
 */
namespace handler;

use db\ImageManager;
use MongoId;

class ImageHandler extends Handler {

    public function getImageAction() {
        $id = new MongoId($this->getData('id'));
        $image = ImageManager::getImage($id);
        $this->sendFile($image);
    }

} 