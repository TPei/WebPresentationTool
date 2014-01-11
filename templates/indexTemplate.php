

<?php
use db\objects\Presentation;
use renderer\HtmlRenderer;

include 'templates/navigation.php';
include 'templates/newPresentationDialog.html';
include 'templates/changePresentationInfo.html';


$user = SessionManager::instance()->getUser();

/** @var array $reversePresentations  */
/** @var Presentation $presentation */

// reverse presentation array to show latest edited presentation first
$reversePresentations = array_reverse($user->getPresentations());

if(empty($reversePresentations))
    include 'templates/noPresentationsTemplate.html';

foreach($reversePresentations as $presentation)
{
    // show presentation thumbnails
    include 'templates/presentationThumbnailTemplate.php';

    // show first slide of each presentations' slides
    $slides = $presentation->getSlides();
    $slide = reset($slides);

    echo HtmlRenderer::slideToHTML($slide, 'templates/xsltPreviewTemplate.xsl');
}