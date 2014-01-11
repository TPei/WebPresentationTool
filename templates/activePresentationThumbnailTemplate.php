<div class="presentationThumbnail">
    <div class="title"><?php echo $presentation->getTitle(); ?></div>
    <div class="summary">Anzahl der Folien: <?php echo count($presentation->getSlides()); ?></div>
    <div class="description">Beschreibung: <?php echo $presentation->getDescription(); ?></div>
    <div class="timestamp">Erstellt: <?php echo $presentation->getTimestamp(); ?></div>
    <div class="author">Author: </div>
    <button data-id="<?php echo $presentation->getId();?>" onclick="spectatePresentation(this.dataset.id, null)">Zuschauen</button>
</div>