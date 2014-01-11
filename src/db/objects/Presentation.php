<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects;

use db\MongoAdapter;
use db\objects\slideElements\ImageElement;
use MongoId;
use SimpleXMLElement;
use db\objects\slideElements\SlideElement;

/**
 * Class Presentation
 * @package db
 * Presentation
 * -> contains slides
 * -> links to author
 */
class Presentation extends DBObject
{

    const COLLECTION_NAME = 'presentations';

    /** @var User */
    private $author;
    const FIELD_AUTHOR = 'author';

    /** @var string */
    private $title;
    const FIELD_TITLE = 'title';

    /** @var string */
    private $description;
    const FIELD_DESCRIPTION = 'description';

    /** @var  string */
    private $active;
    const FIELD_ACTIVE = 'active';

    /** @var string */
    private $timestamp;
    const FIELD_TIMESTAMP = 'timestamp';

    /** @var array */
    private $slides = array();
    const FIELD_SLIDES = 'slides';

    /** @var ImageElement logo */
    private $logo;
    const FIELD_LOGO = 'logo';

    private $activeSlideId;
    const FIELD_ACTIVE_SLIDE_ID = 'activeSlide';

    public function __construct(User $author)
    {
        parent::__construct();
        $this->author = $author;
    }

    /**
     * generates db document from object
     * @return array
     */
    public function toDocument()
    {
        $array = parent::toDocument();

        $array[self::FIELD_TITLE] = $this->title;
        $array[self::FIELD_DESCRIPTION] = $this->description;
        $array[self::FIELD_TIMESTAMP] = $this->timestamp;
        $array[self::FIELD_ACTIVE] = $this->active;
        $array[self::FIELD_ACTIVE_SLIDE_ID] = $this->activeSlideId;
        if ($this->logo != null)
            $array[self::FIELD_LOGO] = $this->logo->toDocument();

        foreach ($this->slides as $slide) {
            /** @var Slide $slide */
            $array[self::FIELD_SLIDES][] = $slide->toDocument();
        }

        return $array;
    }

    /**
     * fills object from db data
     * @param $document
     * @return Presentation
     */
    public function fromDocument($document)
    {
        parent::fromDocument($document);

        $this->title = $document[self::FIELD_TITLE];
        $this->description = $document[self::FIELD_DESCRIPTION];
        $this->timestamp = $document[self::FIELD_TIMESTAMP];
        $this->active = $document[self::FIELD_ACTIVE];
        $this->activeSlideId = $document[self::FIELD_ACTIVE_SLIDE_ID];

        if (isset($document[self::FIELD_LOGO]))
            $this->logo = (new ImageElement())->fromDocument($document[self::FIELD_LOGO]);

        if (empty($document[self::FIELD_SLIDES])) {
            return $this;
        }

        foreach ($document[self::FIELD_SLIDES] as $slideDoc) {
            $this->addSlide((new Slide($this))->fromDocument($slideDoc));
        }

        return $this;
    }

    /**
     * converts object to xml and adds as child to xml element
     * @param SimpleXMLElement $parent
     */
    public function toXml(SimpleXMLElement $parent)
    {
        $document = $this->toDocument();
        $child = $parent->addChild('Presentation');

        // foreach document element, if it's not an array, add as attribute
        foreach ($document as $key => $value)
            if (!is_array($value))
                $child->addAttribute($key, $value);

        // for all slides toXML needs to be called
        /** @var Slide $slide */
        foreach ($this->slides as $slide)
            $slide->toXML($child);
    }

    /**
     * @param Slide $slide
     */
    public function addSlide(Slide $slide)
    {
        $this->slides[$slide->getId()->__toString()] = $slide;
    }

    /**
     * @param Slide $slide
     */
    public function removeSlide(Slide $slide)
    {
        $query = array(
            Presentation::FIELD_SLIDES => array(
                DBObject::FIELD_ID => $slide->getId()
            )
        );
        $criteria = array(DBObject::FIELD_ID => $this->getId());
        MongoAdapter::instance()->removeFromEmbeddedDocument(Presentation::COLLECTION_NAME, $criteria, $query);

        // update slide indeces after removing one slide
        $index = 0;
        foreach ($this->getSlides() as $slide) {
            /** @var Slide $slide */
            $slide->setIndex($index);
            $index += 1;
        }

        unset($this->slides[$slide->getId()->__toString()]);
        MongoAdapter::instance()->save($this, Presentation::COLLECTION_NAME);
    }

    public function removeElement(Slide $slide, SlideElement $element)
    {
        $slide->removeElement($this, $element);
    }

    /**
     * @param $id
     * @return Slide
     */
    public function getSlide($id)
    {
        return $this->slides[$id];
    }

    /**
     * @return \db\objects\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param \db\objects\User $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * @param array $slides
     */
    public function setSlides($slides)
    {
        $this->slides = $slides;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param string $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return MongoId
     */
    public function getActiveSlideId()
    {
        return $this->activeSlideId;
    }

    /**
     * @param mixed $activeSlide
     */
    public function setActiveSlideId($activeSlide)
    {
        $this->activeSlideId = $activeSlide;
    }

    /**
     * @return Slide|null
     */
    public function getActiveSlide()
    {
        $id = $this->activeSlideId;
        if ($id == null)
            return null;
        return $this->getSlide($id);
    }

    /**
     * @return ImageElement
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param ImageElement $logo
     */
    public function setLogo($logo)
    {
        /**
         * adding a logo to a presentation will add the logo to every slide
         * @var Slide $slide
         */
        //foreach($this->getSlides() as $slide)
        //$slide->setLogo($logo);

        $this->logo = $logo;
    }

}