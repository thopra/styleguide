<?php

require_once('../Packages/Libraries/autoload.php');
$styleguide = new \Thopra\Styleguide\Styleguide();
$styleguide->setTitle('Test Styleguide');

$source = new \Thopra\Styleguide\Source\Source(dirname(__FILE__), 'default', 'Fluid source');
$source->setPartialType(\Thopra\Styleguide\Source\AbstractSource::PARTIAL_TYPE_FLUID);

$styleguide->addSource($source);
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

	<?php 
		$source->renderPartial('Test');
	?>

    <script src="../Packages/Libraries/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
