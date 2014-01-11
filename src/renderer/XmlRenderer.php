<?php
/**
 * converts dbobject to xml string
 * @author Thomas Peikert
 */

namespace renderer;

use db\objects\Slide;
use SimpleXMLElement;

class XmlRenderer
{

    /**
     * returns xml string from slide
     * @param Slide $slide
     * @param null $parent
     * @return mixed
     */
    public static function slideToXml(Slide $slide, $parent = null)
    {
        if ($parent == null)
            $parent = new SimpleXMLElement('<xml/>');
        $slide->toXml($parent);
        if ($slide->getPresentation()->getLogo() != null)
            $slide->getPresentation()->getLogo()->toXml($parent);
        return $parent->asXML();
    }

    /**
     * returns xml string with all slides in array
     * @param array $slides
     * @return mixed
     */
    public static function slidesToXml(array $slides)
    {
        $parent = new SimpleXMLElement('<xml/>');
        foreach ($slides as $slide) {
            self::slideToXml($slide, $parent);
        }

        return $parent->asXML();
    }

}