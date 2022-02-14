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

?>

    <div class="layout">
        <br>
        <br>
        <br>
        <br>
    </div>

    <div class="layout">
        <?php include(dirname(__FILE__) . '/components/trip-search.php'); ?>
    </div>

    <div class="layout">
        <br>
        <br>
        <br>
        <br>
    </div>

    <div class="layout">
        <?php createTripsTable($trips); ?>
    </div>

<?php

get_footer();
