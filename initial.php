<?php

    include 'Admin/connect.php';
    // Roots
    $tpl = 'include/templates/'; // Template directory
    $func = 'include/functions/'; // function directory
    $css = 'Themes/CSS/'; // CSS directory
    $js = 'Themes/JS/'; // JS directory
    $img = 'Themes/IMAGES/'; // IMAGES directory
    $user_img = 'docs/images/user_images/'; // user_images directory
    $computer_img = 'docs/images/computer_images/'; // computer_images directory
    $book_img = 'docs/images/book_images/'; // book_images directory

    // Include important links
    include $func . 'function.php'; // Include the function
    include $tpl . 'header.php'; 

    // INCLUDE THE NAVBAR TO ALL PAGES EXCEPT THAT HAS THE VARIABLE noNavbar
    if(!isset($noNavbar)) { include $tpl . 'navbar.php'; };