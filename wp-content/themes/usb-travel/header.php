<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package usb-travel
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>

    <script>window.searchOptions = <?= json_encode(createSearchOptions()) ?></script>
    <script>
        window.stringTranslation = {
            selectCountryPlaceholder: '<?= _e('Select country', 'usb-travel'); ?>',
            selectCampPlaceholder: '<?= _e('Select camp', 'usb-travel'); ?>',
            selectGroupPlaceholder: '<?= _e('Select group', 'usb-travel'); ?>',
            selectTransferPlaceholder: '<?= _e('Select transfer', 'usb-travel'); ?>',
            selectDatePlaceholder: '<?= _e('Select date', 'usb-travel'); ?>',
            selectCountNightsPlaceholder: '<?= _e('Select number of nights', 'usb-travel'); ?>',
        };
    </script>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>


<div>
    <header class="header"></header>
