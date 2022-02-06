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
    if ($data['post_type'] !== 'trip' && $data['post_type'] !== 'camp') {
        return $data;
    }


    $campFieldName = 'field_61e2a01badc25';
    $typeFieldName = 'field_61fd3ca13a4dd';
    $transportTypeFieldName = 'field_61fd3c743a4dc';
    $countDaysFieldName = 'field_61fd3cd43a4de';
    $countNightsFieldName = 'field_61fd3cfa3a4df';
    $priceFieldName = 'field_61fd3d233a4e0';
    $dateFieldName = 'field_61fd3d3f3a4e1';
    $ageFieldName = 'field_61fe4e0d732bf';
    $countryFieldName = 'field_61e27ce8f7de4';

    if ($data['post_type'] === 'trip') {
        $currentCamp = $_POST['acf'][$campFieldName];
        $ruCamp = apply_filters('wpml_object_id', $currentCamp, 'post', TRUE, 'ru');
        $_POST['acf'][$campFieldName] = $ruCamp;

        $currentType = $_POST['acf'][$typeFieldName];
        $ruType = apply_filters('wpml_object_id', $currentType, 'post', TRUE, 'ru');
        $_POST['acf'][$typeFieldName] = $ruType;

        $currentTransportType = $_POST['acf'][$transportTypeFieldName];
        $ruTransportType = apply_filters('wpml_object_id', $currentTransportType, 'post', TRUE, 'ru');
        $_POST['acf'][$transportTypeFieldName] = $ruTransportType;
    } else if ($data['post_type'] === 'camp') {
        $currentCountry = $_POST['acf'][$countryFieldName];
        $ruCountry = apply_filters('wpml_object_id', $currentCountry, 'post', TRUE, 'ru');
        $_POST['acf'][$countryFieldName] = $ruCountry;

    }


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
 * Типы транспорта
 * Записи типов стран и управления страницами вывода
 */

// Инициализируем добавление кастомных типов записей для стран
function register_transport_post_type()
{
    $labels = array(
        'name' => _x('Тип транспорта', 'post type general name'),
        'singular_name' => _x('Тип транспорта', 'post type singular name'),
        'add_new' => 'Добавить тип транспорта'
    );

    $args = array(
        'labels' => $labels,
        'description' => 'Тип транспорта',
        'public' => true,
        'menu_icon' => 'dashicons-car'
    );

    register_post_type('transport_type', $args);
}

add_action('init', 'register_transport_post_type');


/**
 * Настройки админки.
 * Управляем настройками меню, добавляем стили
 */
function wpse_custom_menu_order($menu_ord)
{
    if (!$menu_ord) return true;

    return array(
        'edit.php?post_type=page', // Страницы
        'edit.php?post_type=trip', // Поездки
        'upload.php', // Медиа

        'separator1', // Разделитель

        'indigo-trips-load', // Загрузка поездок

        'separator2',  // Разделитель
        'edit.php?post_type=trip-type', // Типы поездок
        'edit.php?post_type=camp', // Лагеря
        'edit.php?post_type=country', // Страны
        'edit.php?post_type=transport_type', // Тип транспорта
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

/**
 * Настройки параметров поиска.
 */
add_filter('query_vars', function ($vars) {
    $vars[] = 'search_country';
    $vars[] = 'search_camp';
    $vars[] = 'number_people';
    $vars[] = 'transfer';
    $vars[] = 'count_days';
    $vars[] = 'count_nights';
    $vars[] = 'trip_date';
    return $vars;
});


//Функция поиска поездок
function getTrips($options = array())
{
    $args = array(
        'post_type' => 'trip',
        'numberposts' => -1,
        'meta_query' => array(array(
            'key' => 'лагерь',
            'value' => '314',
        )),
    );

    $trips = get_posts($args);
    return $trips;
}

//Функция вывода поездок
function createTripsTable($trips, $options = array())
{

    echo '<div class="trip-table">';

    function createUniqueColKeys($trips)
    {
        $rawColKeys = [];
        $rawGroupKeys = [];

        foreach ($trips as $trip) {
            $meta = get_post_meta($trip->ID);

            $countDaysKey = 'cd' . $meta['количество_дней'][0];
            $countNightsKey = 'cn' . $meta['количество_ночей'][0];
            $ageKey = 'age' . $meta['возраст'][0];
            $colKey = $countDaysKey . $countNightsKey . $ageKey;
            $groupKey = $countDaysKey . $countNightsKey;

            $rawColKeys[] = $colKey;
            $rawGroupKeys[] = $groupKey;
        }

        $colKeys = array_unique($rawColKeys);
        $groupKeys = array_unique($rawGroupKeys);

        $groupedArray = array();

        foreach ($groupKeys as $groupKey) {
            $group = array();
            $group['ages'] = array();
            $group['countDays'] = explode('cn', explode('cd', $groupKey)[1])[0];
            $group['countNights'] = explode('cn', $groupKey)[1];

            foreach ($colKeys as $colKey) {
                $groupColKey = explode('age', $colKey)[0];
                $age = explode('age', $colKey)[1];

                if ($groupKey === $groupColKey) {
                    $group['ages'][] = $age;
                }
            }

            $groupedArray[] = $group;
        }




        return $groupedArray;
    }

    $colKeys = createUniqueColKeys($trips);

    function createHeader($colKeys)
    {
        $cellClass = 'trip-table__cell';
        $cellGroupClass = $cellClass . ' trip-table__cell--group';
        $cellGroupNameClass = 'trip-table__cell-group-name';
        $cellGroupAgesClass = 'trip-table__cell-sub-group';
        $cellGroupAgeClass = 'trip-table__cell-sub-group-inner';

        $ageNames = array(
            '0' => 'Взрослые',
            '1' => 'Дети'
        );

        $dateCell = '<div class="' . $cellClass . '">Дата</div class="' . $cellClass . '">';
        $countryCell = '<div class="' . $cellClass . '">Страна</div>';
        $campCell = '<div class="' . $cellClass . '">Лагерь</div>';
        $typeCell = '<div class="' . $cellClass . '">Группа</div>';
        $transportCell = '<div class="' . $cellClass . '">Трансфер</div>';

        echo '<div class="trip-table__row trip-table__row--header">';

        echo $dateCell;
        echo $countryCell;
        echo $campCell;
        echo $typeCell;
        echo $transportCell;

        foreach ($colKeys as $colKey) {
            echo '<div class="' . $cellGroupClass . '">';

            echo '<div class="' . $cellGroupNameClass . '">' . $colKey['countDays'] . '/' . $colKey['countNights'] . '</div>';

            echo '<div class="' . $cellGroupAgesClass . '">';

            foreach ($colKey['ages'] as $age) {
                echo '<div class="' . $cellGroupAgeClass . '">' . $ageNames[$age] . '</div>';
            }

            echo '</div>';

            echo '</div>';
        }

        echo '</div>';
    }

    createHeader($colKeys);


    echo '</div>';
    return;

    foreach ($trips as $trip) {

        echo '<pre>';
//        print_r($trip);
        echo _e('Good morning', 'usb-travel');
        echo '<br>';
        echo $trip->ID;


        echo '</pre>';

        echo '<br>';
        echo the_field('лагерь', $trip->ID);
        echo '<br>';
        echo '<br>';
//        echo the_field('тип_транспорта', $trip->ID);
//        echo '<br>';
//        echo the_field('тип_поездки', $trip->ID);
//        echo '<br>';
//        echo the_field('количество_дней', $trip->ID);
//        echo '<br>';
//        echo the_field('количество_ночей', $trip->ID);
//        echo '<br>';
//        echo the_field('цена_в_евро', $trip->ID);
//        echo '<br>';
//        echo the_field('дата_поездки', $trip->ID);
//        echo '<br>';
//        echo '<br>';
//        echo '<br>';

    }
}


function loadFrontScripts()
{
    wp_enqueue_script( 'script', get_template_directory_uri(). '/scripts.js' );
//    wp_register_script('load', themplugins_url('/dist/scripts.js', __FILE__));
//    wp_enqueue_script('load');
}


function loadFrontStyles()
{
    wp_enqueue_style( 'style', get_template_directory_uri(). '/style.css' );
}

add_action('wp_enqueue_scripts', 'loadFrontStyles');
add_action('wp_enqueue_scripts', 'loadFrontScripts');
