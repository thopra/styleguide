<?php

require_once('../Packages/Libraries/autoload.php');
$styleguide = new \Thopra\Styleguide\Styleguide();
$styleguide->setTitle('Test Styleguide');

$source = new \Thopra\Styleguide\Source\Source(dirname(__FILE__), 'default', 'Example Styles');
$styleguide->addSource($source);

$styleguide->render();

?>
