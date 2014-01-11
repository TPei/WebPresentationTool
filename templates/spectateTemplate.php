<?php
/**
 * @author Thomas Peikert
 */
use db\MongoAdapter;
use db\objects\Presentation;
use db\objects\User;
use renderer\HtmlRenderer;

include 'navigation.php';
?>
<div id="activePresentations">

    <h1>Zur Zeit aktive Presentationen:</h1>
    <?php
    $users = MongoAdapter::instance()->findAll(User::COLLECTION_NAME);

    foreach ($users as $cursor) {

        /** @var User $user */
        $user = new User();
        $user->fromDocument($cursor);
        //$user = SessionManager::instance()->getUser();

        /** @var array $reversePresentations */
        /** @var Presentation $presentation */

        // reverse presentation array to show latest edited presentation first
        $reversePresentations = array_reverse($user->getPresentations());

        if (empty($reversePresentations))
            echo '<p>momentan werden keine Präsentationen gezeigt</p>';

        foreach ($reversePresentations as $presentation) {
            // if active flag is set to true, show presentation here
            if ($presentation->getActive() == 'true')
            {
                // show presentation thumbnails
                include 'activePresentationThumbnailTemplate.php';

                // show first slide of each presentations' slides
                $slides = $presentation->getSlides();
                $slide = reset($slides);

                echo HtmlRenderer::slideToHTML($slide, 'templates/xsltPreviewTemplate.xsl');
            }
        }
    }
    ?>

</div>