<?php
/**
 * @author Thomas Peikert
 */
use db\MongoAdapter;
use db\objects\Presentation;
use db\objects\Slide;
use db\objects\User;

include '../bootstrap.php';

/** @var Presentation $presentation  */
$presentationDocument = MongoAdapter::instance()->findById('5292599f6803fad91216b86d', Presentation::COLLECTION_NAME);

$userDocument = MongoAdapter::instance()->findById('5292599f6803fad91216b86c', Presentation::COLLECTION_NAME);
$user = new User();
$user->fromDocument($userDocument);

$presentation = new Presentation($user);
$presentation->fromDocument($presentationDocument);


$slides = $presentation->getSlides();

/** @var Slide $slide  */
$slide = reset($slides);

//$presentation->removeSlide($slide);

$elements = $slide->getElements();
$element = reset($elements);

$slide->removeElement($presentation, $element);
