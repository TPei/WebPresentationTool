<?php
/**
 * @author Thomas Peikert
 */
namespace handler;

use db\ImageManager;
use db\objects\Presentation;
use db\objects\Slide;
use db\objects\slideElements\AbstractTextElement;
use db\objects\slideElements\ImageElement;
use db\objects\slideElements\OrderedListElement;
use db\objects\slideElements\SlideElement;
use db\objects\slideElements\UnorderedListElement;
use renderer\HtmlRenderer;
use db\MongoAdapter;
use MongoId;
use db\PresentationManager;
use SessionManager;

class SlideHandler extends AjaxHandler
{

    /**
     * change the active slide
     */
    public function changeSlideAction()
    {
        $id = $this->ajaxData('id');

        // post active user from session
        $user = SessionManager::instance()->getUser();

        // post active presentation's id from session
        $presentationId = SessionManager::instance()->getActivePresentationId();

        // post presentation via id
        $presentation = $user->getPresentation($presentationId);

        // post slide by selected slide id
        /** @var Slide $slide */
        $slide = $presentation->getSlide($id);
        SessionManager::instance()->setActiveSlideId($slide->getId()->__toString());

        // rerender slide to html
        $slideHtml = HtmlRenderer::slideToHTML($slide, 'templates/xsltTemplate.xsl');

        // response
        $response = array('html' => $slideHtml, 'id' => $slide->getId()->__toString());

        $this->sendAsJSON($response);
    }

    /**
     * change the active slide in presentation mode
     */
    public function changePresentationSlideAction()
    {
        $command = $this->ajaxData('command');

        // post active user from session
        $user = SessionManager::instance()->getUser();

        // post active presentation's id from session
        $presentationId = SessionManager::instance()->getActivePresentationId();
        $slideId = SessionManager::instance()->getActiveSlideId();

        // post presentation via id
        $presentation = $user->getPresentation($presentationId);
        $id = null;
        if ($command == 'next')
            $id = $this->getNextSlide($slideId);
        else if ($command == 'previous')
            $id = $this->getPreviousSlide($slideId);
        else if ($command == 'first')
            $id = $this->getFirstSlide();
        else if ($command == 'last')
            $id = $this->getLastSlide();

        if ($id == null)
            $id = $slideId;

        // post slide by selected slide id
        /** @var Slide $slide */
        $slide = $presentation->getSlide($id);

        // save presentation mode active slide
        $presentation->setActiveSlideId($id);
        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

        SessionManager::instance()->setActiveSlideId($slide->getId()->__toString());

        // rerender slide to html
        $slideHtml = HtmlRenderer::slideToHTML($slide, 'templates/xsltPresentationTemplate.xsl');
        $slideHtml = str_replace("<?xml version=\"1.0\"?>", "", $slideHtml);

        // response
        $response = array('html' => $slideHtml, 'id' => $slide->getId()->__toString());

        $this->sendAsJSON($response);
    }

    /**
     * get the next slide
     * @param string $id id of current slide
     * @return string next slide id
     */
    protected function getNextSlide($id)
    {
        $flag = false;

        $user = SessionManager::instance()->getUser();
        $presentationId = SessionManager::instance()->getActivePresentationId();
        $presentation = $user->getPresentation($presentationId);

        /** @var Slide $slide */
        foreach ($presentation->getSlides() as $slide) {
            if ($flag)
                return $slide->getId()->__toString();
            else {
                if ($slide->getId()->__toString() == $id)
                    $flag = true;
            }
        }
    }

    /**
     * get previous slide in presentation
     * @param string $id of current slide
     * @return string id of previous slide
     */
    protected function getPreviousSlide($id)
    {
        $flag = false;

        $user = SessionManager::instance()->getUser();
        $presentationId = SessionManager::instance()->getActivePresentationId();
        $presentation = $user->getPresentation($presentationId);

        $slides = array_reverse($presentation->getSlides());

        /** @var Slide $slide */
        foreach ($slides as $slide) {
            if ($flag)
                return $slide->getId()->__toString();
            else {
                if ($slide->getId()->__toString() == $id)
                    $flag = true;
            }
        }
    }

    /**
     * get first slide in presentation
     * @return mixed id of first slide
     */
    protected function getFirstSlide()
    {
        $user = SessionManager::instance()->getUser();
        $presentationId = SessionManager::instance()->getActivePresentationId();
        $presentation = $user->getPresentation($presentationId);

        $slides = $presentation->getSlides();
        $slide = reset($slides);

        return $slide->getId()->__toString();

    }

    /**
     * get last slide in presentation
     * @return mixed id of last slide
     */
    protected function getLastSlide()
    {
        $user = SessionManager::instance()->getUser();
        $presentationId = SessionManager::instance()->getActivePresentationId();
        $presentation = $user->getPresentation($presentationId);

        $slides = array_reverse($presentation->getSlides());
        $slide = reset($slides);

        return $slide->getId()->__toString();

    }

    /**
     * edit an element
     */
    public function editElementAction()
    {
        $elementId = $this->ajaxData('elementId');
        $text = $this->ajaxData('text');

        // post active user from session
        $user = SessionManager::instance()->getUser();

        // post active presentation's id from session
        $presentationId = SessionManager::instance()->getActivePresentationId();

        // post presentation via id
        $presentation = $user->getPresentation($presentationId);

        // post slide by selected slide id

        $slideId = SessionManager::instance()->getActiveSlideId();

        /** @var Slide $slide */
        $slide = $presentation->getSlide($slideId);

        /** @var SlideElement $selectedElement */
        $selectedElement = $slide->getElement($elementId);

        switch ($selectedElement->getType()) {
            case UnorderedListElement::Type:
            case OrderedListElement::Type:
                /** @var OrderedListElement $selectedElement */
                $selectedElement->setElements(array($text));
                break;
            default:
                /** @var AbstractTextElement $selectedElement */
                $selectedElement->setText($text);

        }

        // rerender slide to html
        //$slideHtml = HtmlRenderer::slideToHTML($slide, 'templates/xsltTemplate.xsl');

        // save updated presentation
        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

        // response
        $response = array('success' => true, 'text' => $text);
        $this->sendAsJSON($response);
    }

    /**
     * edit an element's size
     */
    public function editElementDimensionsAction()
    {
        $elementId = $this->ajaxData('id');
        $width = $this->ajaxData('width');


        // post active user from session
        $user = SessionManager::instance()->getUser();

        // post active presentation's id from session
        $presentationId = SessionManager::instance()->getActivePresentationId();

        // post presentation via id
        $presentation = $user->getPresentation($presentationId);

        // post slide by selected slide id
        $slideId = SessionManager::instance()->getActiveSlideId();

        /** @var Slide $slide */
        $slide = $presentation->getSlide($slideId);

        $selectedElement = $slide->getElement($elementId);

        if ($selectedElement instanceof ImageElement) {
            $factor = $selectedElement->getWidth() / $selectedElement->getHeight();
            $selectedElement->setHeight($width / $factor);
        }

        $selectedElement->setWidth($width);

        // rerender slide to html
        //$slideHtml = HtmlRenderer::slideToHTML($slide, 'templates/xsltTemplate.xsl');

        // save updated presentation
        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);
        // response
        $response = array('success' => true, 'id' => $slideId);

        $this->sendAsJSON($response);

    }

    /**
     * edit an elements' position
     */
    public function editElementPositionAction()
    {
        $elementId = $this->ajaxData('id');
        $left = $this->ajaxData('left');
        $top = $this->ajaxData('top');

        // post active user from session
        $user = SessionManager::instance()->getUser();

        // post active presentation's id from session
        $presentationId = SessionManager::instance()->getActivePresentationId();

        // post presentation via id
        $presentation = $user->getPresentation($presentationId);

        // post slide by selected slide id
        $slideId = SessionManager::instance()->getActiveSlideId();

        /** @var Slide $slide */
        $slide = $presentation->getSlide($slideId);

        $selectedElement = $slide->getElement($elementId);

        // workaround because saved position is relative to slide, gotten position is relative to window...
        $selectedElement->setX($left - 144);
        $selectedElement->setY($top - 46);

        // rerender slide to html
        //$slideHtml = HtmlRenderer::slideToHTML($slide, 'templates/xsltTemplate.xsl');

        // save updated presentation
        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);
        // response
        $response = array('success' => true);

        $this->sendAsJSON($response);
    }

    /**
     * delete an element
     */
    public function deleteElementAction()
    {
        $elementId = $this->ajaxData('elementId');

        // post active user from session
        $user = SessionManager::instance()->getUser();

        // post active presentation's id from session
        $presentationId = SessionManager::instance()->getActivePresentationId();

        // post presentation via id
        $presentation = $user->getPresentation($presentationId);

        // post slide by selected slide id
        $slideId = SessionManager::instance()->getActiveSlideId();

        /** @var Slide $slide */
        $slide = $presentation->getSlide($slideId);

        $element = $slide->getElement($elementId);

        // if the element is an imageelement, also delete image
        if ($element instanceof ImageElement) {
            /** @var ImageElement $element */
            $refId = $element->getImageRef();
            ImageManager::deleteImage($refId);
        }

        $slide->removeElement($presentation, $element);


        // --------// --------// --------// --------// --------// --------
        // rerender slide to html
        $slideHtml = HtmlRenderer::slideToHTML($slide, 'templates/xsltTemplate.xsl');

        // response
        $response = array('html' => $slideHtml);
        //$response = array('html' => $element);

        $this->sendAsJSON($response);
    }

    /**
     * delete the active slide
     */
    public function deleteActiveSlideAction()
    {
        // post active user from session
        $user = SessionManager::instance()->getUser();

        // post active presentation's id from session
        $presentationId = SessionManager::instance()->getActivePresentationId();

        // post presentation via id
        $presentation = $user->getPresentation($presentationId);

        // post slide by selected slide id
        $slideId = SessionManager::instance()->getActiveSlideId();

        /** @var Slide $slide */
        $slide = $presentation->getSlide($slideId);

        foreach ($slide->getElements() as $element) {
            if ($element instanceof ImageElement) {
                // if element is image, also copy referenced image
                /** @var ImageElement $element */
                $refId = $element->getImageRef();
                ImageManager::deleteImage($refId);
            }
        }

        // remove slide, only if there is more than one slide left
        if (count($presentation->getSlides()) > 1)
            $presentation->removeSlide($slide);

        // return presentation id so that an presentation update can be requested
        $this->sendAsJSON(array('id' => $presentationId, 'slideId' => $slideId));

    }

    /**
     * duplicate the active slide
     */
    public function duplicateActiveSlideAction()
    {
        // post active user from session
        $user = SessionManager::instance()->getUser();

        // post active presentation's id from session
        $presentationId = SessionManager::instance()->getActivePresentationId();

        // post presentation via id
        $presentation = $user->getPresentation($presentationId);

        // post slide by selected slide id
        $slideId = SessionManager::instance()->getActiveSlideId();

        /** @var Slide $slide */
        $slideOld = $presentation->getSlide($slideId);

        $slide = clone $slideOld;
        $slide->setId(new MongoId());


        /** @var SlideElement $element */
        foreach ($slide->getElements() as $element) {
            if ($element instanceof ImageElement) {
                // if element is image, also copy referenced image
                /** @var ImageElement $element */
                $refId = $element->getImageRef();
                ImageManager::copyImage($refId);
            }

            $element->setId(new MongoId());
        }

        $presentation->addSlide($slide);
        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

        $this->sendAsJSON(array('success' => true, 'id' => $presentationId));
    }

    /**
     * get the current slide's index
     */
    public function getSlideIndexAction()
    {

        $user = SessionManager::instance()->getUser();

        $presentation = PresentationManager::findPresentationById(SessionManager::instance()->getActivePresentationId(), $user);

        /** @var Slide $slide */
        $slide = $presentation->getSlide(SessionManager::instance()->getActiveSlideId());
        $index = $slide->getIndex();
        $this->sendAsJSON(array('index' => $index));
    }

} 