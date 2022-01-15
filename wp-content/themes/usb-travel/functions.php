<?php
/**
 * usb-travel functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package usb-travel
 */

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.0.0');
}


// Инициализируем добавление кастомных типов записей для лагерей
add_action('init', 'register_camps_post_type');

function register_camps_post_type()
{
    $labels = array(
        'name' => _x('Лагеря', 'post type general name'),
        'singular_name' => _x('Лагерь', 'post type singular name'),
    );
    // Set various pieces of information about the post type
    $args = array(
        'labels' => $labels,
        'description' => 'Лагеря',
        'public' => true,
    );
    // Register the movie post type with all the information contained in the $arguments array
    register_post_type('camp', $args);
}

add_filter('manage_camp_posts_columns', 'camps_columns_head', 10);
add_action('manage_camp_posts_custom_column', 'camps_columns_content', 10, 2);

function camps_columns_head($defaults)
{
    $defaults['country'] = 'Страна';
    $defaults['id'] = 'Id (Для таблицы)';
    unset($defaults['date']);
    return $defaults;
}

function camps_columns_content($column_name, $post_ID)
{
    if ($column_name == 'id') {
        echo $post_ID;
    }

    if ($column_name == 'country') {
        $countryId = get_field('страна', $post_ID);
        echo get_the_title($countryId);
    }
}


// Инициализируем добавление кастомных типов записей для стран
add_action('init', 'register_countries_post_type');

function register_countries_post_type()
{
    $labels = array(
        'name' => _x('Страны', 'post type general name'),
        'singular_name' => _x('Страна', 'post type singular name'),
    );
    // Set various pieces of information about the post type
    $args = array(
        'labels' => $labels,
        'description' => 'Страны',
        'public' => true,
    );
    // Register the movie post type with all the information contained in the $arguments array
    register_post_type('country', $args);
}


