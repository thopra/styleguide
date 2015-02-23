# styleguide
Implementation of [kss-php](https://github.com/scaninc/kss-php) intended to help quickly creating a styleguide.
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

## Additional Features
In Addition to the basic [KSS-Syntax](https://github.com/kneath/kss/blob/master/SPEC.md), kss-php features a "Markup:" comment section in which you can provide some example Markup. This library extends this idea with a new Keyword: "Partial", that specifies a path to a Partial, that should be rendered as the example Markup.

    /*
     * Partial: Components/Dropdown
     *
     * PartialParams: {"foo": "bar"}
     */

Should render the contents of <Path to Partials in Source>/Components/Dropdown.phtml while setting the variable $foo to "bar".


