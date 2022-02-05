<?php
/*
Plugin Name: Indigo Trips Load
Description: Плагин для загрузки поездок из файла
Version: 1.0
Author: Yury Ustinov
License: GPL2
*/

add_action('admin_menu', 'top_tags_register_admin_page');

function top_tags_register_admin_page()
{

    add_menu_page(
        'Загрузка поездок',
        'Загрузка поездок из XLSX',
        'manage_options',
        'indigo-trips-load',
        'indigo_trips_load_render_admin_page',
        'dashicons-cloud-upload'
    );
}

function indigo_trips_load_render_admin_page()
{
    include plugin_dir_path(__FILE__) . 'templates/index.php';
}

function loadScripts()
{
    wp_register_script('load', plugins_url('/dist/scripts.js', __FILE__));
    wp_enqueue_script('load');
}

function loadStyles()
{
    wp_register_style('load', plugins_url('/dist/style.css', __FILE__));
    wp_enqueue_style('load');
}

add_action('admin_enqueue_scripts', 'loadScripts');
add_action('admin_enqueue_scripts', 'loadStyles');

//Функция получения объекта типов транспорта
function getTransportTypeValues()
{
//Получаем все типы транспорта
    $args = array(
        'post_type' => 'transport_type',
        'numberposts' => -1,
    );

    $transportTypes = get_posts($args);

// Собираем ID транспорта
    $links = [];
    foreach ($transportTypes as $transportType) {
        $link = apply_filters('wpml_object_id', $transportType->ID, 'post', TRUE, 'ru');
        $links[] = $link;
    }

//Формируем массив русских ID и тайтлов
    $linksUnique = array_unique($links);
    $transportTypesUnique = [];
    foreach ($linksUnique as $link) {
        $transportType = array(
            'id' => $link,
            'title' => get_the_title($link),
        );

        $transportTypesUnique[] = $transportType;
    }

    return $transportTypesUnique;

}

//Функция получения объекта лагерей
function getCampsValues()
{
//Получаем все лагеря
    $args = array(
        'post_type' => 'camp',
        'numberposts' => -1,
    );

    $camps = get_posts($args);

// Собираем ID лагерей
    $links = [];
    foreach ($camps as $camp) {
        $link = apply_filters('wpml_object_id', $camp->ID, 'post', TRUE, 'ru');
        $links[] = $link;
    }

//Формируем массив русских ID и тайтлов
    $linksUnique = array_unique($links);
    $campsUnique = [];
    foreach ($linksUnique as $link) {
        $camp = array(
            'id' => $link,
            'title' => get_the_title($link),
        );

        $campsUnique[] = $camp;
    }

    return $campsUnique;

}

//Функция получения объекта лагерей
function getTripsTypesValues()
{
//Получаем все лагеря
    $args = array(
        'post_type' => 'trip-type',
        'numberposts' => -1,
    );

    $tripsTypesPosts = get_posts($args);


// Собираем ID лагерей
    $links = [];
    foreach ($tripsTypesPosts as $tripsType) {
        $link = apply_filters('wpml_object_id', $tripsType->ID, 'post', TRUE, 'ru');
        $links[] = $link;
    }

//Формируем массив русских ID и тайтлов
    $linksUnique = array_unique($links);
    $tripsTypesUnique = [];
    foreach ($linksUnique as $link) {
        $tripsType = array(
            'id' => $link,
            'title' => get_the_title($link),
        );

        $tripsTypesUnique[] = $tripsType;
    }

    return $tripsTypesUnique;

}


function createSelectOptions($array)
{
    foreach ($array as $arrayItem) {
        echo '<option value="' . $arrayItem['id'] . '">' . $arrayItem['title'] . '</option>';
    }
}


add_action('wp_ajax_ajax_create_trips', 'ajax_create_trips');

function ajax_create_trips()
{
    $tripsArray = json_decode(stripcslashes($_REQUEST['data']), true);

    // Проверяем, есть ли данные
    if ($tripsArray === null) {
        wp_send_json_error(
            new WP_Error('-1', 'Неправильный формат или в файле нет поездок')
        );
    }

    if (!isset($tripsArray['tripsArray'])) {
        wp_send_json_error(
            new WP_Error('-1', 'Неправильный формат или в файле нет поездок')
        );
    }

    if (!isset($tripsArray['camp'])) {
        wp_send_json_error(
            new WP_Error('-1', 'Не указан лагерь')
        );
    }

    if (!isset($tripsArray['type'])) {
        wp_send_json_error(
            new WP_Error('-1', 'Не указан тип (10+1/11+1)')
        );
    }

    if (!isset($tripsArray['transportType'])) {
        wp_send_json_error(
            new WP_Error('-1', 'Не указан трансфер')
        );
    }

    $camp = $tripsArray['camp'];
    $campName = get_the_title($camp);
    $transportType = $tripsArray['transportType'];
    $transportName = get_the_title($transportType);
    $type = $tripsArray['type'];
    $typeName = get_the_title($type);
    $ageNames = array(
        '0' => 'Взрослые',
        '1' => 'Дети'
    );


    foreach ($tripsArray['tripsArray'] as $trip) {
        if (!is_int($trip['countDays']) || !is_int($trip['countNights']) || !is_int($trip['price'])) {
            wp_send_json_error(
                new WP_Error('-1', 'Ошибка в поездке')
            );
        }

        $countDays = $trip['countDays'];
        $countNights = $trip['countNights'];
        $price = $trip['price'];
        $date = $trip['date'];
        $age = $trip['age'];

        $postCampName = $campName . ', ';
        $postDate = date_format(date_create($date), 'd.m.Y') . ', ';
        $postCounts = $countDays . 'д./' . $countNights . 'н., ';
        $postOptions = $ageNames[$age] . ', ' . $price . ' евро';
        $postTransportName = $transportName . ', ';
        $postType = $typeName . ', ';

        $postName = $postDate . $postTransportName . $postType . $postCampName . $postCounts . $postOptions;

        // Create post object
        $newPost = array(
            'post_title' => wp_strip_all_tags($postName),
            'post_type' => 'trip',
            'post_status' => 'publish',
        );

        // Insert the post into the database
        $postId = wp_insert_post($newPost);

        update_field('field_61e2a01badc25', $camp, $postId);
        update_field('field_61fd3ca13a4dd', $type, $postId);
        update_field('field_61fd3c743a4dc', $transportType, $postId);
        update_field('field_61fd3cd43a4de', $countDays, $postId);
        update_field('field_61fd3cfa3a4df', $countNights, $postId);
        update_field('field_61fd3d233a4e0', $price, $postId);
        update_field('field_61fd3d3f3a4e1', $date, $postId);
        update_field('field_61fe4e0d732bf', $age, $postId);

    }

    wp_send_json_success();

    wp_die();

}


?>
