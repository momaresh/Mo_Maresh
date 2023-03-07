<?php
    // This initial file contain all the roots that we will use in our backend webpage
    require 'connect.php'; // This will link to the connect file of our database
    // Roots
    $tpl = 'include/templates/'; // Template directory
    $func = 'include/functions/'; // function directory
    $css = 'Themes/CSS/'; // CSS directory
    $js = 'Themes/JS/'; // JS directory
    $img = 'Themes/IMAGES/'; // IMAGES directory

    // Include important links
    include $func . 'function.php'; // Include the function file
    include $tpl . 'header.php'; // Include our header file

    // INCLUDE THE NAVBAR TO ALL PAGES EXCEPT THAT HAS THE VARIABLE noNavbar
    if(!isset($noNavbar)) { include $tpl . 'navbar.php'; }; // Sometimes we do not need the navbar like in login