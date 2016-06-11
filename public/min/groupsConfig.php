<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 *
 * See http://code.google.com/p/minify/wiki/CustomSource for other ideas
 **/

return array(
    'js.plain' => array(
        '//assets/js/jquery-2.1.4.min.js',
        '//assets/js/bootstrap.min.js',
        '//assets/js/ekko-lightbox.min.js',
    ),
    'css.plain' => array(
        '//assets/css/bootstrap.min.css', 
        '//assets/css/ekko-lightbox.css',
        '//assets/css/ekko-dark.css',
        '//assets/css/ionicons.min.css', 
        '//assets/css/theme.css',
        '//assets/css/ltcircular.css',
        '//assets/css/foam.css'
    ),
    'js.default' => array(
        '//assets/js/jquery-2.1.4.min.js',
        '//assets/js/bootstrap.min.js',
        '//assets/js/slick.min.js',
        '//assets/js/sharecount.js',
        '//assets/js/ekko-lightbox.min.js',
        '//assets/js/app.js'
    ),
    'css.default' => array(
        '//assets/css/bootstrap.min.css', 
        '//assets/css/ekko-lightbox.css',
        '//assets/css/ekko-dark.css',
        '//assets/css/ionicons.min.css', 
        '//assets/css/typicons/font/typicons.min.css',
        '//assets/css/slick.css',
        '//assets/css/theme.css',
        '//assets/css/postbox.css',
        //'//assets/css/foam.css',
        '//assets/css/circle-tiles.css'        
    )
);
