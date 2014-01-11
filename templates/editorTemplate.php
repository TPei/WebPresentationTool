<?php
use renderer\HtmlRenderer;

/**
 * @author Thomas Peikert
 */
/** @var db\objects\Presentation $presentation */
/** @var Template $this */

include 'templates/navigation.php';
//include 'templates/newPresentationDialog.html';
//include 'templates/editBar.php';
include 'linkInfo.html';
include 'list.html';

$presentation = $this->getExtra('presentation');

// save presentation as active presentation in session
SessionManager::instance()->setActivePresentationId($presentation->getId()->__toString());

$slides = $presentation->getSlides();
/** @var db\objects\Slide $slide  */
$slide = reset($slides);
SessionManager::instance()->setActiveSlideId($slide->getId()->__toString());
echo '<div id="wrapper">';
echo '<div id="editorWrapper">';
echo '<div class="slidesContainer">';
include 'slidesBarTemplate.php';
echo '</div>';

echo '<div id="activeSlideContainer" class="activeSlideContainer">'.HtmlRenderer::slideToHTML($slide, 'templates/xsltTemplate.xsl').'</div>';

include 'elementBar.php';
echo '</div>';


echo '</div>';

echo '<div id="lengthCalculator"></div>';