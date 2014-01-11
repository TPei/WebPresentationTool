<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/style.less"/>
</head>
<body>
<?php
/**
 * @author Thomas Peikert
 */
use db\MongoAdapter;
use db\objects\User;

include '../bootstrap.php';

MongoAdapter::instance()->dropDatabase();

$user = DummyGenerator::createUser();
DummyGenerator::createPresentationWithTitle($user, 'Testpräsentation 0');
DummyGenerator::createPresentationWithTitle($user, 'Testpräsentation 1');

$cursor = MongoAdapter::instance()->findAll(User::COLLECTION_NAME);
foreach ($cursor as $doc) {
    $user = (new User())->fromDocument($doc);

    //echo HtmlRenderer::slidesToHTML($user->getPresentations()[0]->getSlides(), 'templates/xsltTemplate.xsl');
}
?>
</body>
</html>
