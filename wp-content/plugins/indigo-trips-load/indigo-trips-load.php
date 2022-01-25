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

//
//add_action('wp_ajax_my_action', 'my_action_callback');
//
//function my_action_callback()
//{
//
//    $whatever = intval($_POST['whatever']);
//
//    $whatever += 10;
//    echo $whatever;
//
//    // выход нужен для того, чтобы в ответе не было ничего лишнего,
//    // только то что возвращает функция
//    wp_die();
//}

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

    $tripsTypes = [];
    foreach ($tripsTypesPosts as $tripTypePost) {
        $trip = array(
            'id' => $tripTypePost->ID,
            'title' => $tripTypePost->post_title,
        );

        $tripsTypes[] = $trip;
    }

    return $tripsTypes;

}


function createSelectOptions($array) {
    foreach ($array as $arrayItem) {
        echo '<option value="' . $arrayItem['id'] . '">' . $arrayItem['title'] . '</option>';
    }
}

?>
