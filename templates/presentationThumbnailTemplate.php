<?php
/**
 * @author Thomas Peikert
 */

/** @var db\objects\Presentation $presentation */
?>
<div class="presentationThumbnail">
    <div class="title"><?php echo $presentation->getTitle(); ?></div>
    <div class="summary">Anzahl der Folien: <?php echo count($presentation->getSlides()); ?></div>
    <div class="description">Beschreibung: <?php echo $presentation->getDescription(); ?></div>
    <div class="timestamp">Erstellt: <?php echo $presentation->getTimestamp(); ?></div>
    <button data-id="<?php echo $presentation->getId();?>" onclick="showPresentation(this.dataset.id)">edit</button>
    <button data-id="<?php echo $presentation->getId();?>" onclick="deletePresentation(this.dataset.id)">delete</button>
    <button data-id="<?php echo $presentation->getId();?>" onclick="giveThisPresentation(this.dataset.id)">present</button>
    <button data-id="<?php echo $presentation->getId();?>" onclick="copyPresentation(this.dataset.id)">duplicate</button>
    <button data-id="<?php echo $presentation->getId();?>" onclick="changePresentationDialog(this.dataset.id)">change Info</button>
</div>