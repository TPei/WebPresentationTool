<?php
/**
 * @author Thomas Peikert
 */
namespace handler;

use db\ImageManager;
use db\objects\Presentation;
use db\objects\Slide;
use db\objects\slideElements\AbstractTextElement;
use db\objects\slideElements\HeadingElement;
use db\objects\slideElements\HeadingElementSmall;
use db\objects\slideElements\HorizontalDividerElement;
use db\objects\slideElements\ImageElement;
use db\objects\slideElements\LinkElement;
use db\objects\slideElements\ListElement;
use db\objects\slideElements\OrderedListElement;
use db\objects\slideElements\QuoteElement;
use db\objects\slideElements\SlideElement;
use db\objects\slideElements\TextElement;
use db\objects\slideElements\UnorderedListElement;
use db\objects\User;
use renderer\HtmlRenderer;
use db\MongoAdapter;
use MongoId;
use db\PresentationManager;
use SessionManager;

class PresentationHandler extends AjaxHandler {

    /**
     * create a presentation with title and description and add it to current user
     */
    public function createPresentationAction() {
        $title = $this->ajaxData('title');
        $description = $this->ajaxData('description');

        // post active user from session
        $user = SessionManager::instance()->getUser();

        $presentation = new Presentation($user);
        $presentation->setTitle($title);
        $presentation->setDescription($description);
        $presentation->setTimestamp(date('d M Y H:i:s'));
        $presentation->setActive(false);

        $slide = new Slide($presentation);
        $index = count($user->getPresentations());
        $slide->setIndex($index);

        // set meta info on every slide
        /*$slide->setAuthor($user->getUsername());
        $slide->setTitle($presentation->getTitle());
        $slide->setTimestamp($presentation->getTimestamp());
        $slide->setDescription($description);*/

        $slide->addElement($this->generateTextElement('Neue Präsentation', 0));

        $presentation->addSlide($slide);

        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);
        $user->addPresentation($presentation);
        MongoAdapter::instance()->save($user, User::COLLECTION_NAME);

        $this->sendAsJSON(array('success' => true));
    }

    /**
     * update presentation title and description
     */
    public function changePresentationAction() {
        $title = $this->ajaxData('title');
        $description = $this->ajaxData('description');
        $id = $this->ajaxData('id');

        // post active user from session
        $user = SessionManager::instance()->getUser();

        $presentation = PresentationManager::findPresentationById($id, $user);
        $presentation->setTitle($title);
        $presentation->setDescription($description);

        /**
         * update title on every slide
         * @var Slide $slide
         */
        foreach($presentation->getSlides() as $slide)
        {
            //$slide->setTitle($title);
            //$slide->setDescription($presentation->getDescription());
        }

        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);
        $this->sendAsJSON(array('success' => true));
    }

    /**
     * get Presentation Info
     */
    public function getPresentationInfoAction() {
        $id = $this->ajaxData('id');
        $presentation = PresentationManager::findPresentationById($id, SessionManager::instance()->getUser());
        $response = array('title' => $presentation->getTitle(), 'description' => $presentation->getDescription());
        $this->sendAsJSON($response);
    }

    /**
     * delete a presentation and delete presentation reference in user
     */
    public function deletePresentationAction() {
        $presentationId = $this->ajaxData('presentation');

        $user = SessionManager::instance()->getUser();
        $presentation = $user->getPresentation($presentationId);

        if($presentation->getLogo() != null)
        {
            // if element is image, also copy referenced image
            /** @var ImageElement $element */
            $refId = $presentation->getLogo()->getImageRef();
            ImageManager::deleteImage($refId);
        }

        foreach($presentation->getSlides() as $slide)
        {
            /** @var Slide $slide */
            foreach ($slide->getElements() as $element) {
                if($element instanceof ImageElement)
                {
                    // if element is image, also copy referenced image
                    /** @var ImageElement $element */
                    $refId = $element->getImageRef();
                    ImageManager::deleteImage($refId);
                }
            }
        }

        $user->deletePresentation($presentation);

        $this->sendAsJSON(array('success' => true));
    }

    /**
     * start the active presentation
     */
    public function startActivePresentationAction() {
        // update database presentation active status -> true, return success => true
        $user = SessionManager::instance()->getUser();
        $presentationId = SessionManager::instance()->getActivePresentationId();
        $presentation = $user->getPresentation($presentationId);

        $slideId = SessionManager::instance()->getActiveSlideId();
        $presentation->setActiveSlideId($slideId);
        $presentation->setActive(true);
        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

        $this->sendAsJSON(array('success' => true));
    }

    /**
     * start the presentation of which the id was given
     */
    public function startPresentationAction() {
        $presentationId = $this->ajaxData('id');
        $user = SessionManager::instance()->getUser();
        $presentation = $user->getPresentation($presentationId);

        $slides = $presentation->getSlides();
        /** @var Slide $slide */
        $slide = reset($slides);
        $slideId = $slide->getId();
        SessionManager::instance()->setActivePresentationId($presentationId);
        SessionManager::instance()->setActiveSlideId($slideId);
        $presentation->setActiveSlideId($slideId->__toString());
        $presentation->setActive(true);

        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

        $this->sendAsJSON(array('success' => true));
    }

    /**
     * stop a presentation
     */
    public function endPresentationAction() {
        $user = SessionManager::instance()->getUser();
        $presentationId = SessionManager::instance()->getActivePresentationId();

        $presentation = $user->getPresentation($presentationId);
        $presentation->setActive(false);
        $presentation->setActiveSlideId(null);
        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

        $this->sendAsJSON(array('success' => true));
    }

    /**
     * watch a presentation
     */
    public function spectatePresentationAction() {

        $id = $this->ajaxData('id');

        $presentation = PresentationManager::findPresentationById($id);

        //$slide = $presentation->getActiveSlide();
        $slideId = $presentation->getActiveSlideId();
        $slide = $presentation->getSlide($slideId);

        if ($slide != null) {
            // rerender slide to html
            $index = $slide->getIndex();
            $slideHtml = HtmlRenderer::slideToHTML($slide, 'templates/xsltPresentationTemplate.xsl');
            $response = array('active' => true, 'html' => $slideHtml, 'slideNumber' => $index);
        } else {
            $response = array('active' => false);
        }

        $this->sendAsJSON($response);
    }

    /**
     * generate a text element
     * @param $text
     * @param $z
     * @return TextElement
     */
    private function generateTextElement($text, $z) {
        $textElement = new TextElement();
        $textElement->setText($text);
        $textElement->setX(rand(10, 500));
        $textElement->setY(rand(10, 400));
        $textElement->setZ($z);
        return $textElement;
    }

    /**
     * add a slide to the current presentation
     */
    protected function addSlideAction() {
        $user = SessionManager::instance()->getUser();
        $presentationId = SessionManager::instance()->getActivePresentationId();
        $presentation = $user->getPresentation($presentationId);
        $slide = new Slide($presentation);
        $slide->setIndex(count($presentation->getSlides()));
        $element = $this->generateSlideElement(new HeadingElement(), 'Neue Folie', 0, 100, 25);
        $slide->addElement($element);

        /*
        // add meta info to every slide
        $slide->setAuthor($user->getUsername());
        $slide->setTitle($presentation->getTitle());
        $slide->setTimestamp($presentation->getTimestamp());
        $slide->setDescription($presentation->getDescription());
        $slide->setLogo($presentation->getLogo());
        */

        $presentation->addSlide($slide);
        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

        $response = array('presentationId' => $presentationId, 'slideId' => $slide->getId()->__toString());
        $this->sendAsJSON($response);
    }

    /**
     * generate a SlideElement
     * @param AbstractTextElement $element type of element
     * @param string $text
     * @return AbstractTextElement
     */
    protected function generateSlideElement(AbstractTextElement $element, $text) {
        $element->setText($text);
        $element->setX(20);
        $element->setY(55);
        return $element;
    }

    /**
     * add an element to the current slide
     */
    public function addElementToSlideAction() {

        $user = SessionManager::instance()->getUser();
        $presentationId = SessionManager::instance()->getActivePresentationId();
        $presentation = $user->getPresentation($presentationId);
        /** @var Slide $slide */
        $slide = $presentation->getSlide(SessionManager::instance()->getActiveSlideId());

        $elementName = $this->ajaxData('element');
        $text = $this->ajaxData('text');
        $width = $this->ajaxData('width');
        $height = $this->ajaxData('height');

        $element = null;
        switch ($elementName) {
            case 'h1':
                $element = new HeadingElement();
                $element = $this->generateSlideElement($element, $text);
                break;
            case 'h3':
                $element = new HeadingElementSmall();
                $element = $this->generateSlideElement($element, $text);
                break;
            case 'p':
                $element = new TextElement();
                $element = $this->generateSlideElement($element, $text);
                break;
            case 'q':
                $element = new QuoteElement();
                $element = $this->generateSlideElement($element, $text);
                break;
            case 'a':
                /** @var LinkElement $element */
                $element = $this->generateSlideElement(new LinkElement(), $text);
                $element->setLink("http://www.google.de");
                break;
            case 'ol':
                $element = $this->generateListElement(new OrderedListElement(), $text, 0, $width, $height);
                break;
            case 'ul':
                $element = $this->generateListElement(new UnorderedListElement(), $text, 0, $width, $height);
                break;
            case 'hr':
                $element = $this->generateSlideElement(new HorizontalDividerElement(), "", 0, $width, $height);
                break;
            default:
                $element = $this->generateSlideElement(new TextElement(), $text, 0, $width, $height);
                break;
        }


        $slide->addElement($element);
        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

        $response = array('id' => $slide->getId()->__toString());
        $this->sendAsJSON($response);
    }


    /**
     * add a link to the current slide
     */
    public function addLinkToSlideAction() {

        $user = SessionManager::instance()->getUser();
        $presentationId = SessionManager::instance()->getActivePresentationId();
        $presentation = $user->getPresentation($presentationId);
        /** @var Slide $slide */
        $slide = $presentation->getSlide(SessionManager::instance()->getActiveSlideId());

        $title = $this->ajaxData('title');
        $link = $this->ajaxData('link');

        $element = new LinkElement();
        $element->setText($title);
        $element->setLink($link);
        $element->setX(10);
        $element->setY(75);

        //print_r($element);


        $slide->addElement($element);
        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

        $response = array('id' => $slide->getId()->__toString());
        $this->sendAsJSON($response);
    }

    /**
     * add a list to the currrent slide
     * add a list to the currrent slide
     */
    public function addListToSlideAction() {
        $user = SessionManager::instance()->getUser();
        $presentationId = SessionManager::instance()->getActivePresentationId();
        $presentation = $user->getPresentation($presentationId);
        /** @var Slide $slide */
        $slide = $presentation->getSlide(SessionManager::instance()->getActiveSlideId());

        $unordered = $this->ajaxData('unordered');
        $elements = $this->ajaxData('elements');

        /** @var ListElement $list */
        $list = null;

        if ($unordered)
            $list = new UnorderedListElement();
        else
            $list = new OrderedListElement();

        $list->setElements($elements);

        $slide->addElement($list);
        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);


        $this->sendAsJSON(array('success' => true, 'id' => $slide->getId()->__toString()));
    }

    public function changeSlideIndexAction()
    {
        $newIndex = $this->ajaxData('index');

        $user = SessionManager::instance()->getUser();

        $presentationId = SessionManager::instance()->getActivePresentationId();
        $presentation = PresentationManager::findPresentationById($presentationId, $user);
        $slides = $presentation->getSlides();

        $slideId = SessionManager::instance()->getActiveSlideId();
        $activeSlide = $presentation->getSlide($slideId);

        // Todo: remove active slide from slides array, then insert it at the index position, then reindex all slides

        /** @var Slide $slide */
        foreach($slides as $key => $slide)
    {
        if($slide->getId() == $activeSlide->getId())
        {
            unset($slides[$key]);
        }
    }

        $slides = $this->insertIntoArray($slides, $activeSlide, $newIndex);

        $presentation->setSlides($slides);


        // renumber all slides
        $count = 0;

        /** @var Slide $slide */
        foreach($presentation->getSlides() as $slide)
        {
        $slide->setIndex($count);
        $count++;
    }

        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);

        echo json_encode(array('success' => true, 'presentationId' => $presentationId, 'slideId' => $slideId));
    }

    /**
     * inserts an element into an array at a given position
     * @param array $array for the element to be inserted into
     * @param Slide $slide to insert
     * @param int $index for the element to be inserted
     * @return array new array containing the element at the correct position
     */
    public function insertIntoArray($array, $slide, $index)
    {
        // if the index is farther back than the array is long add to end
        if($index >= count($array))
        {
            array_push($array, $slide);
            return $array;
        }
        // if it is 0, simply shift the entire array
        else if($index == 0)
        {
            array_unshift($array, $slide);
            return $array;
        }

        // else split the array at the given index
        $leftArray = array_slice($array, 0, $index);
        $rightArray = array_slice($array, $index);

        // then add the slide to the end of the left array
        array_push($leftArray, $slide);

        // and merge the arrays
        return array_merge($leftArray, $rightArray);
    }

    /**
     * copy a presentation by deleting all id's (presentation, slides, elements)
     * and then saving again
     */
    public function copyPresentationAction() {
        $id = $this->ajaxData('id');

        $user = SessionManager::instance()->getUser();

        $presentation = PresentationManager::findPresentationById($id, $user);

        $presentation->setTitle($presentation->getTitle() . " (Kopie)");
        $presentation->setId(new MongoId());

        if($presentation->getLogo() != null)
        {
            // if element is image, also copy referenced image
            /** @var ImageElement $element */
            $refId = $presentation->getLogo()->getImageRef();
            ImageManager::copyImage($refId);
        }

        /** @var Slide $slide */
        foreach ($presentation->getSlides() as $slide) {
            $slide->setId(new MongoId());
            //$slide->setTitle($presentation->getTitle());

            /** @var SlideElement $element */
            foreach ($slide->getElements() as $element) {
                if($element instanceof ImageElement)
                {
                    // if element is image, also copy referenced image
                    /** @var ImageElement $element */
                    $refId = $element->getImageRef();
                    ImageManager::copyImage($refId);
                }
                $element->setId(new MongoId());
            }
        }

        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);
        $user->addPresentation($presentation);
        MongoAdapter::instance()->save($user, User::COLLECTION_NAME);

        $this->sendAsJSON(array('success' => true));
    }

    /**
     * @param ListElement $element
     * @param string $text
     * @param int $z
     * @param int $width
     * @param int $height
     * @return ListElement
     */
    private function generateListElement(ListElement $element, $text, $z, $width, $height) {
        $element->getElements()[] = $text;
        $element->setWidth($width);
        $element->setHeight($height);
        $element->setX(20);
        $element->setY(55);
        $element->setZ($z);
        return $element;
    }
}


