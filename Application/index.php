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
    <link rel="stylesheet" href="css/styleguide.css" />
    <link rel="stylesheet" href="../Packages/Libraries/components/jqueryui/themes/start/jquery-ui.min.css" />

</head>
<body>

	<?php $styleguide->renderFrame(); ?>

</body>
</html>
