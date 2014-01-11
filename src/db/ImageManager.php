<?php

namespace db;

use db\objects\DBObject;
use File;
use MongoGridFSFile;
use MongoId;

class ImageManager {

    const IMAGE_COLLECTION = 'images';
    const EXT = 'ext';

    private static $mimes = array(
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
    );

    /**
     * reduces reference count
     * if refcount == 0 -> delete image
     * @param MongoId $id
     * @return bool
     */
    public static function deleteImage(MongoId $id) {
        $gridFile = self::gridFs()->findOne(array(DBObject::FIELD_ID => $id));
        $gridFile->file['refCount']--;
        if ($gridFile->file['refCount'] == 0)
            return self::gridFs()->delete($id);
        self::update($gridFile);
        return true;
    }

    /**
     * increase reference count
     * @param MongoId $id
     */
    public static function copyImage(MongoId $id) {
        $gridFile = self::gridFs()->findOne(array(DBObject::FIELD_ID => $id));
        $gridFile->file['refCount']++;
        self::update($gridFile);
    }

    /**
     * @param MongoGridFSFile $gridFile
     */
    private static function update(MongoGridFSFile $gridFile) {
        self::gridFs()->update(array(DBObject::FIELD_ID => $gridFile->file[DBObject::FIELD_ID]), $gridFile->file);
    }

    /**
     * @param $inputFieldName
     * @return MongoId
     */
    public static function saveUpload($inputFieldName) {
        $path = $_FILES[$inputFieldName]['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return self::gridFs()->storeUpload($inputFieldName, array(self::EXT => $ext, 'refCount' => 1));
    }

    /**
     * @param MongoId $id
     * @return File
     */
    public static function getImage(MongoId $id) {
        $gridFile = self::gridFs()->findOne(array(DBObject::FIELD_ID => $id));
        $imageData = $gridFile->getBytes();
        $ext = $gridFile->file['ext'];
        $image = new File();
        $image->mime = self::mimeFromExtension($ext);
        $image->fileData = $imageData;
        $image->filename = $gridFile->file;
        return $image;
    }

    /**
     * @return \MongoGridFS
     */
    private static function gridFs() {
        return MongoAdapter::instance()->getResourceDb()->getGridFS(self::IMAGE_COLLECTION);
    }

    /**
     * @param $ext
     * @return string
     */
    private static function mimeFromExtension($ext) {
        return self::$mimes[$ext];
    }

}
