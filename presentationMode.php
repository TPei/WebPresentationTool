<?php include 'bootstrap.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <link rel="stylesheet/less" type="text/css" href="css/style.less"/>
    <link rel="stylesheet/less" type="text/css" href="css/elementStyles.less"/>
    <link rel="stylesheet" type="text/css" href="css/drag.css"/>

    <script src="script/loginHandler.js"></script>
    <script src="script/presentationHandler.js"></script>
    <script src="script/slideHandler.js"></script>
    <script src="script/viewHandler.js"></script>
    <script src="script/presentationMode.js"></script>
    <script src="script/addToPresentation.js"></script>
    <script src="script/script.js"></script>
    <script src="script/dragAndDrop.js"></script>
    <script src="script/imageHandler.js"></script>
    <script src="script/less.js"></script>
    <script src="script/Ajax.js"></script>

</head>
<body>
<div id="mainView">
    <?php
    $template = new Template(Template::VIEW_SHOW);
    $id = SessionManager::instance()->getActivePresentationId();
    $presentation = SessionManager::instance()->getUser()->getPresentation($id);
    $template->putExtra('presentation', $presentation);
    echo $template->getContent();
    ?>
</div>
</body>
</html>