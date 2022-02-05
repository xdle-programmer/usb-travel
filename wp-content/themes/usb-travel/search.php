<?php

/*
Template Name: Результаты поиска
*/

get_header();

//Разбираю параметры
function getUrlQuery($url, $key = null)
{
    $parts = parse_url($url);
    if (!empty($parts['query'])) {
        parse_str($parts['query'], $query);
        if (is_null($key)) {
            return $query;
        } elseif (isset($query[$key])) {
            return $query[$key];
        }
    }

    return false;
}

$options = getUrlQuery($_SERVER['REQUEST_URI']);


$trips = getTrips();


createTripsTable($trips);

//echo '<pre>';
//
//
//print_r(getTrips());
//echo '</pre>';


//function getCampsIds()
//{
//    $args = array(
//        'post_type' => 'camp',
//        'numberposts' => -1,
//    );
//
//    $camps = get_posts($args);
//    $campsIds = [];
//
//
//    foreach ($camps as $camp) {
//
//        $link = apply_filters('wpml_object_id', $camp->ID, 'post', TRUE, 'ru');
//
////        icl_object_id( get_the_ID(), $post_type, false, ICL_LANGUAGE_CODE ); // Returns the ID of the current custom post
//
//        echo '<br>';
////        echo $camp->ID . '  ' . get_the_title($camp->ID) . $camp->ID . '12312312312313----------';
//        echo $link;
//        echo '<br>';
//        echo '<br>';
//        echo '<br>';
//        echo '<br>';
//
//        $campsIds[] = $camp->ID;
//    }
//
//    return $campsIds;
//}
//
////function getCamp()
////{
////}
//
//
//print_r(getCampsIds());

?>

    <h1>Страница поиска</h1>

<?php

get_footer();
