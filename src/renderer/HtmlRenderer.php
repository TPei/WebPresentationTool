<?php
/**
 * converts xml string into html string
 * @author Thomas Peikert
 */

namespace renderer;

use db\objects\Slide;
use DOMDocument;
use XSLTProcessor;

class HtmlRenderer
{
    /**
     * returns hmtl string from slide
     * @param Slide $slide
     * @param $template
     * @return string
     */
    public static function slideToHtml(Slide $slide, $template)
    {
        $xmlString = XmlRenderer::slideToXml($slide);

        // load xml string
        $xml = new DOMDocument();
        $xml->loadXML($xmlString);

        // start xslt
        $xslt = new XSLTProcessor();
        $xsl = new DOMDocument();
        $xsl->load($template, LIBXML_NOCDATA);
        $xslt->importStylesheet($xsl);

        return $xslt->transformToXML($xml);
    }

    /**
     * returns html string with all slides in array
     * @param array $slides
     * @param $template
     * @return string
     */
    public static function slidesToHTML(array $slides, $template)
    {
        $html = '';
        foreach($slides as $slide)
            $html .= self::slideToHtml($slide, $template);

        return $html;
    }
}