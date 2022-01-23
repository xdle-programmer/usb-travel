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

function loadStyles() {
    wp_register_style('load', plugins_url('/dist/style.css', __FILE__));
    wp_enqueue_style('load');
}

add_action('admin_enqueue_scripts', 'loadScripts');
add_action('admin_enqueue_scripts', 'loadStyles');
?>
