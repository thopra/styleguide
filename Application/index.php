<?php

require_once('../Packages/Libraries/autoload.php');
$styleguide = new \Thopra\Styleguide\Styleguide('less', 'Example Styleguide');

$bootstrap = new \Thopra\Styleguide\Source\BootstrapSource();
//exclude section "code"
$bootstrap->excludeSections(array('1.1'));
$styleguide->addSource($bootstrap);

$typo3 = new \Thopra\Styleguide\Source\Typo3Source();
$styleguide->addSource($typo3);

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <title>Styleguide</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/styleguide.css" />

    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="_contrib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>

</head>
<body>

	<?php $styleguide->render(); ?>

    <script src="../Packages/Libraries/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
