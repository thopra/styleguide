<?php

ini_set('display_errors', 'on');

require_once('../Packages/Libraries/autoload.php');
$styleguide = new \Thopra\Styleguide\Styleguide();
$styleguide->setTitle('Test Styleguide');

$source = new \Thopra\Styleguide\Source\Source(dirname(__FILE__).'/Examples/Default', 'default', 'Default');

$fluidSource = new \Thopra\Styleguide\Source\Source(dirname(__FILE__).'/Examples/Fluid', 'fluid', 'Fluid');
$fluidSource->setPartialType(\Thopra\Styleguide\Source\AbstractSource::PARTIAL_TYPE_FLUID);

$twigSource = new \Thopra\Styleguide\Source\Source(dirname(__FILE__).'/Examples/Twig', 'twig', 'Twig');
$twigSource->setPartialType(\Thopra\Styleguide\Source\AbstractSource::PARTIAL_TYPE_TWIG);

$styleguide->addSource($source);
$styleguide->addSource($fluidSource);
$styleguide->addSource($twigSource);
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
