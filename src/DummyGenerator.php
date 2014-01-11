<?php
/**
 * @author Thomas Peikert
 */

use db\MongoAdapter;
use db\objects\Presentation;
use db\objects\Slide;
use db\objects\User;
use db\objects\slideElements\HeadingElement;
use db\objects\slideElements\TextElement;

class DummyGenerator {

    static function createPresentationWithTitle(User $user, $title) {
        $presentation = new Presentation($user);
        $presentation->setTitle($title);
        $presentation->setDescription('It\'s an awesome presentation about presentations, dawg!');
        $presentation->setTimestamp(date(' H:i:s'));

        for ($i = 0; $i < 12; $i++) {
            $slide = self::generateSlide($i, $presentation);
            $text = self::generateTextElement('Presentation: ' . $title, 0, 0, 0, 200, 40);
            $slide->addElement($text);
            $presentation->addSlide($slide);
        }

        MongoAdapter::instance()->save($presentation, Presentation::COLLECTION_NAME);
        $user->addPresentation($presentation);

        MongoAdapter::instance()->save($user, User::COLLECTION_NAME);
    }

    static function createPresentation(User $user) {
        self::createPresentationWithTitle($user, 'Testpräsentation');
    }

    static function createUser() {
        $user = new User();
        $user->setUsername('Thomas');
        $user->setPassword(sha1('test'));
        return $user;
    }


    static function generateSlide($index, Presentation $presentation) {
        $slide = new Slide($presentation);
        $slide->setIndex($index);

        for ($i = 0; $i < rand(1, 10); $i++) {
            //$text = self::generateTextElement('slide: '.$index.' textelement: '.$i, $i*50+50, $i*50+50, $i, 72, 72);
            $text2 = self::generateHeadingElement('slide: ' . $index . ' textelement: ' . $i, $i * 50 + 50, $i * 50 + 50, $i, 72, 72);

            //$slide->addElement($text);
            $slide->addElement($text2);
        }

        return $slide;
    }

    static function generateHeadingElement($text, $x, $y, $z, $width, $height) {
        $textElement = new HeadingElement();
        $textElement->setText($text);
        $textElement->setX($x);
        $textElement->setY($y);
        $textElement->setZ($z);
        $textElement->setWidth($width);
        $textElement->setHeight($height);
        return $textElement;
    }

    static function generateTextElement($text, $x, $y, $z, $width, $height) {
        $textElement = new TextElement();
        $textElement->setText($text);
        $textElement->setX($x);
        $textElement->setY($y);
        $textElement->setZ($z);
        $textElement->setWidth($width);
        $textElement->setHeight($height);
        return $textElement;
    }


}