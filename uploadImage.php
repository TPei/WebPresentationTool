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

$file = $_FILES['imageUploadForm'];

// get native image size
$imagePath = $file['tmp_name'];
$dimensions = getimagesize($imagePath);
$width = $dimensions[0];
$height = $dimensions[1];

$ratio = $width / $height;
$baseSize = 100;
$relativeSize = intval($baseSize / $ratio);

$imageId = ImageManager::saveUpload('imageUploadForm');

$user = SessionManager::instance()->getUser();
$presentation = $user->getPresentation(SessionManager::instance()->getActivePresentationId());
$slide = $presentation->getSlide(SessionManager::instance()->getActiveSlideId());

$image = new ImageElement();
$image->setImageRef($imageId);
$image->setX(100);
$image->setY(100);
$image->setWidth($baseSize);
$image->setHeight($relativeSize);

$slide->addElement($image);

MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

$slideHtml = HtmlRenderer::slideToHTML($slide, 'templates/xsltTemplate.xsl');


echo json_encode(array('success' => true, 'html' => $slideHtml));