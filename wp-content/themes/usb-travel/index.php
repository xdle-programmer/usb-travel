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

    <div>Главная страница</div>

<?php
echo '<pre>';

function getCampsIds()
{
    $args = array(
        'post_type' => 'camp',
        'numberposts' => -1,
    );

    $camps = get_posts($args);
    $campsIds = [];


    foreach ($camps as $camp) {

        $link = apply_filters('wpml_object_id', $camp->ID, 'post', TRUE, 'ru');

//        icl_object_id( get_the_ID(), $post_type, false, ICL_LANGUAGE_CODE ); // Returns the ID of the current custom post

        echo '<br>';
//        echo $camp->ID . '  ' . get_the_title($camp->ID) . $camp->ID . '12312312312313----------';
        echo $link;
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';

        $campsIds[] = $camp->ID;
    }

    return $campsIds;
}

//function getCamp()
//{
//}


print_r(getCampsIds());
echo '</pre>';
?>

<?php

get_footer();
