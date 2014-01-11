<?php
use db\ImageManager;
use db\MongoAdapter;
use db\objects\Presentation;
use db\objects\slideElements\ImageElement;
use renderer\HtmlRenderer;

/**
 * @author Thomas Peikert
 */

include 'bootstrap.php';

$file = $_FILES['logoUploadForm'];
$id = $_POST['id'];

// get native image size
$imagePath = $file['tmp_name'];
$dimensions = getimagesize($imagePath);
$width = $dimensions[0];
$height = $dimensions[1];


$ratio = $width / $height;
$baseSize = 100;
$relativeSize = intval($baseSize / $ratio);

$imageId = ImageManager::saveUpload('logoUploadForm');

$user = SessionManager::instance()->getUser();
$presentation = $user->getPresentation($id);

$image = new ImageElement();
$image->setImageRef($imageId);
$image->setWidth($baseSize);
$image->setHeight($relativeSize);

$logo = $presentation->getLogo();
if($logo != null)
{
    // if element is image, also copy referenced image
    /** @var ImageElement $element */
    $refId = $logo->getImageRef();
    ImageManager::deleteImage($refId);
}

$presentation->setLogo($image);

//print_r($presentation);

MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

echo json_encode(array('success' => true, 'id' => $id));