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


/**
 * Лагеря
 * Записи лагерей и управления страницами вывода
 */

function register_camps_post_type()
{
    $labels = array(
        'slug' => 'camp',
        'name' => _x('Лагеря', 'post type general name'),
        'singular_name' => _x('Лагерь', 'post type singular name'),
        'add_new' => 'Добавить лагерь'
    );
    // Set various pieces of information about the post type
    $args = array(
        'labels' => $labels,
        'description' => 'Лагеря',
        'public' => true,
        'menu_icon' => 'dashicons-admin-multisite'
    );

    register_post_type('camp', $args);
}

add_action('init', 'register_camps_post_type');

function camps_columns_head($defaults)
{
    $defaults['country'] = 'Страна';
    $defaults['id'] = 'Id (Для таблицы)';
    unset($defaults['date']);
    return $defaults;
}

add_filter('manage_camp_posts_columns', 'camps_columns_head', 10);

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

add_action('manage_camp_posts_custom_column', 'camps_columns_content', 10, 2);


/**
 * Поездки.
 * Записи поездок и управления страницами вывода
 */

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
        'menu_icon' => 'dashicons-airplane'
    );

    register_post_type('trip', $args);
}

add_action('init', 'register_trips_post_type');

function trips_columns_head($defaults)
{
    $defaults['camp'] = 'Лагерь';
    unset($defaults['date']);
    return $defaults;
}

add_filter('manage_trip_posts_columns', 'trips_columns_head', 10);

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

add_action('manage_trip_posts_custom_column', 'trips_columns_content', 10, 2);

//Функция замены id лагеря с любого языка на русский, для корректной привязки
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

add_filter('wp_insert_post_data', 'replace_camp_id_in_trip', 10, 2);


/**
 * Типы поездок.
 * Записи поездок и управления страницами вывода
 */
add_action('init', 'register_trips_types_post_type');

function register_trips_types_post_type()
{
    $labels = array(
        'name' => _x('Типы поездок', 'post type general name'),
        'singular_name' => _x('Тип поездки', 'post type singular name'),
        'add_new' => 'Добавить тип поездки'
    );

    // Set various pieces of information about the post type
    $args = array(
        'labels' => $labels,
        'description' => 'Типы поездок',
        'public' => true,
        'menu_icon' => 'dashicons-admin-generic'
    );

    register_post_type('trip-type', $args);
}


/**
 * Страны
 * Записи стран и управления страницами вывода
 */

// Инициализируем добавление кастомных типов записей для стран
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
        'menu_icon' => 'dashicons-admin-site-alt'
    );

    register_post_type('country', $args);
}

add_action('init', 'register_countries_post_type');


/**
 * Настройки админки.
 * Управляем настройками меню, добавляем стили
 */
function wpse_custom_menu_order($menu_ord)
{
    if (!$menu_ord) return true;

    return array(
        'edit.php?post_type=camp', // Лагеря
        'edit.php?post_type=page', // Страницы
        'upload.php', // Медиа

        'separator1', // Разделитель

        'indigo-trips-load', // Загрузка поездок

        'separator2',  // Разделитель
        'edit.php?post_type=trip', // Поездки
        'edit.php?post_type=trip-type', // Типы поездок
        'edit.php?post_type=country', // Страны
        'separator-last', // Разделитель

        'link-manager.php', // Links
        'edit-comments.php', // Comments
        'edit.php', // Posts
        'themes.php', // Appearance
        'plugins.php', // Plugins
        'users.php', // Users
        'tools.php', // Tools
        'options-general.php', // Settings
        'index.php', // Dashboard
    );
}

add_filter('custom_menu_order', 'wpse_custom_menu_order', 10, 1);

add_filter('menu_order', 'wpse_custom_menu_order', 10, 1);

function admin_styles()
{
    wp_register_style('admin-styles', get_stylesheet_directory_uri() . '/admin/style.css');
    wp_enqueue_style('admin-styles');
}

add_action('admin_enqueue_scripts', 'admin_styles');

