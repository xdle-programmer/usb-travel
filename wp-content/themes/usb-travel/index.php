<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package usb-travel
 */

get_header();
?>
    <div class="layout">
        <br>
        <br>
        <br>
    </div>
    <div class="layout">
        <?php include(dirname(__FILE__) . '/components/trip-search.php'); ?>
    </div>

<?php

get_footer();
