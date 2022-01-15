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
        'add_new' => 'Добавить лагерь'
    );
    // Set various pieces of information about the post type
    $args = array(
        'labels' => $labels,
        'description' => 'Лагеря',
        'public' => true,
        'menu_position' => 2,
        'menu_icon' => 'dashicons-admin-multisite'
    );

//    menu_icon

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
        'add_new' => 'Добавить страну'
    );

    $args = array(
        'labels' => $labels,
        'description' => 'Страны',
        'public' => true,
    );

    register_post_type('country', $args);
}


// Инициализируем добавление кастомных типов записей для поездок
add_action('init', 'register_trips_post_type');

function register_trips_post_type()
{
    $labels = array(
        'name' => _x('Поездки', 'post type general name'),
        'singular_name' => _x('Поездка', 'post type singular name'),
        'add_new' => 'Добавить поездку'
    );

    // Set various pieces of information about the post type
    $args = array(
        'labels' => $labels,
        'description' => 'Поездки',
        'public' => true,
        'menu_position' => 2,
        'menu_icon' => 'dashicons-airplane'
    );

    register_post_type('trip', $args);
}

add_filter('manage_trip_posts_columns', 'trips_columns_head', 10);
add_action('manage_trip_posts_custom_column', 'trips_columns_content', 10, 2);

function trips_columns_head($defaults)
{
    $defaults['camp'] = 'Лагерь';
    unset($defaults['date']);
    return $defaults;
}

function trips_columns_content($column_name, $post_ID)
{
    if ($column_name == 'id') {
        echo $post_ID;
    }

    if ($column_name == 'camp') {
        $countryId = get_field('лагерь', $post_ID);
        echo get_the_title($countryId);
    }
}

//Функция замены id лагеря с любого языка на русский, для корректной привязки
add_filter('wp_insert_post_data', 'replace_camp_id_in_trip', 10, 2);

function replace_camp_id_in_trip($data)
{
    if ($data['post_type'] !== 'trip') {
        return $data;
    }

    $campFieldName = 'field_61e2a01badc25';

    $currentCamp = $_POST['acf'][$campFieldName];
    $ruCamp = apply_filters('wpml_object_id', $currentCamp, 'post', TRUE, 'ru');
    $_POST['acf'][$campFieldName] = $ruCamp;

    return $data;
}


//// Создаем массив данных новой записи
//    $post_data = array(
//        'post_title' => 'Тайтл из функции внутри, ага',
//        'post_type' => 'trip',
//    );
//
//    // Вставляем запись в базу данных
//    $post_id = wp_insert_post($post_data);
