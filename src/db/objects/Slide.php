<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects;

use db\objects\slideElements\HeadingElementSmall;
use db\objects\slideElements\HorizontalDividerElement;
use db\objects\slideElements\ImageElement;
use db\objects\slideElements\OrderedListElement;
use db\objects\slideElements\UnorderedListElement;
use db\MongoAdapter;
use SimpleXMLElement;
use db\objects\slideElements\HeadingElement;
use db\objects\slideElements\LinkElement;
use db\objects\slideElements\QuoteElement;
use db\objects\slideElements\SlideElement;
use db\objects\slideElements\TextElement;

/**
 * Class Slide
 * @package db
 * Slide
 * -> contains SlideElements
 */
class Slide extends DBObject
{

    /** @var array */
    private $elements = array();
    const FIELD_ELEMENTS = 'elements';

    /** @var int */
    private $index;
    const FIELD_INDEX = 'index';

    protected $presentation;

    public function __construct(Presentation $presentation)
    {
        parent::__construct();
        $this->presentation = $presentation;
    }

    /**
     * generates db document from object
     * @return array
     */
    public function toDocument()
    {
        $array = parent::toDocument();
        foreach ($this->elements as $element) {
            /** @var SlideElement $element */
            $array[self::FIELD_ELEMENTS][] = $element->toDocument();
        }
        $array[self::FIELD_INDEX] = $this->index;
        return $array;
    }

    /**
     * fills object from db data
     * @param $document
     * @return Slide
     */
    public function fromDocument($document)
    {
        parent::fromDocument($document);

        if (empty($document[Slide::FIELD_ELEMENTS])) {
            $this->index = $document[Slide::FIELD_INDEX];
            return $this;
        }

        foreach ($document[Slide::FIELD_ELEMENTS] as $elementDoc) {
            switch ($elementDoc[SlideElement::FIELD_TYPE]) {
                // check SlideElement type and create object accordingly
                case TextElement::Type:
                    self::addElement((new TextElement())->fromDocument($elementDoc));
                    break;
                case HeadingElement::Type:
                    self::addElement((new HeadingElement())->fromDocument($elementDoc));
                    break;
                case HeadingElementSmall::Type:
                    self::addElement((new HeadingElementSmall())->fromDocument($elementDoc));
                    break;
                case HorizontalDividerElement::Type:
                    self::addElement((new HorizontalDividerElement())->fromDocument($elementDoc));
                    break;
                case QuoteElement::Type:
                    self::addElement((new QuoteElement())->fromDocument($elementDoc));
                    break;
                case OrderedListElement::Type:
                    self::addElement((new OrderedListElement())->fromDocument($elementDoc));
                    break;
                case UnorderedListElement::Type:
                    self::addElement((new UnorderedListElement())->fromDocument($elementDoc));
                    break;
                case LinkElement::Type:
                    self::addElement((new LinkElement())->fromDocument($elementDoc));
                    break;
                case ImageElement::Type:
                    self::addElement((new ImageElement())->fromDocument($elementDoc));
                    break;
            }
        }

        $this->index = $document[Slide::FIELD_INDEX];
        return $this;
    }

    /**
     * converts object to xml and adds as child to xml element
     * @param SimpleXMLElement $parent
     */
    public function toXml(SimpleXMLElement $parent)
    {
        $document = $this->toDocument();
        $child = $parent->addChild('Slide');
        foreach ($document as $key => $value) {
            if (!is_array($value)) {
                $child->addAttribute($key, $value);
            }
        }

        $logo = $this->getPresentation()->getLogo();

        if($logo != null)
        {
            $grandchild = $child->addChild("logo");
            $grandchild->addAttribute('imageRef', $logo->getImageRef());
        }

        $child->addAttribute('author', $this->getPresentation()->getAuthor()->getUsername());
        $child->addAttribute('title', $this->getPresentation()->getTitle());

        $maxLength = 50;
        $descriptionShown = $this->getPresentation()->getDescription();
        if(strlen($descriptionShown) > $maxLength)
            $descriptionShown = substr($descriptionShown, 0, $maxLength).'...';

        $child->addAttribute('description', $descriptionShown);
        $child->addAttribute('timestamp', $this->getPresentation()->getTimestamp());


        /** @var SlideElement $element */
        foreach ($this->elements as $element) {
            $element->toXML($child);
        }
    }

    /**
     * @param SlideElement $element
     */
    public function addElement(SlideElement $element)
    {
        $this->elements[$element->getId()->__toString()] = $element;
    }


    public function removeElement(Presentation $presentation, SlideElement $element)
    {
        $criteria = array(
            DBObject::FIELD_ID => $presentation->getId(),
            Presentation::FIELD_SLIDES . '.' . DBObject::FIELD_ID => $this->getId()
        );

        $query = array(
            Presentation::FIELD_SLIDES . '.$.' . Slide::FIELD_ELEMENTS => array(
                DBObject::FIELD_ID => $element->getId()
            )
        );
        MongoAdapter::instance()->removeFromEmbeddedDocument(Presentation::COLLECTION_NAME, $criteria, $query);

        // also remove from slide (not only db document)
        $id = $element->getId()->__toString();
        unset($this->elements[$id]);
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param $id
     * @return SlideElement
     */
    public function getElement($id)
    {
        return $this->elements[$id];
    }

    /**
     * @param array $elements
     */
    public function setElements($elements)
    {
        if (!$elements == null)
            $this->elements = $elements;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return Presentation
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @param int $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }


}