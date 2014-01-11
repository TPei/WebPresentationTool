<?php
use renderer\HtmlRenderer;

/**
 * @author Thomas Peikert
 */
/** @var db\objects\Presentation $presentation */
/** @var Template $this */

$presentation = $this->getExtra('presentation');

// save presentation as active presentation in session
SessionManager::instance()->setActivePresentationId($presentation->getId()->__toString());

$slides = $presentation->getSlides();
/** @var db\objects\Slide $slide  */
$slide = reset($slides);
SessionManager::instance()->setActiveSlideId($slide->getId()->__toString());

echo HtmlRenderer::slidesToHTML($slides, 'templates/xsltThumbnailTemplate.xsl');
