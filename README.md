# styleguide
Implementation of kss-php intended to help quickly creating a styleguide.
This is still in very early development.

## Use composer to get the dependencies

    composer install

## Create a styleguide

    <?php
  
    require_once('../Packages/Libraries/autoload.php');
    $styleguide = new \Thopra\Styleguide\Styleguide('less', 'Example Styleguide');
    
    ?>
    
    <!DOCTYPE html>
    <html>
    <head>
        <title>My Styleguide</title>
    </head>
    <body>
    
    	<?php $styleguide->render(); ?>
    
    </body>
    </html>
    
### Add a reference source to the styleguide

    $bootstrap = new \Thopra\Styleguide\Source\BootstrapSource();
    $styleguide->addSource($bootstrap);
    
#### You can also exclude sections from the styleguide

    $bootstrap = new \Thopra\Styleguide\Source\BootstrapSource();
    //exclude section "code"
    $bootstrap->excludeSections(array('1.1'));

#### Add your own additional source
(currently, the package only comes with twitter bootstrap)

    $myReference = new \Thopra\Styleguide\Source\Source('path/to/files', "myKey", "My Reference Title");
    $styleguide->addSource($myReference);
    
Or you can write your own source class that implements \Thopra\Styleguide\Source\SourceInterface


