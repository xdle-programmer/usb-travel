<div class="trip-search">

    <div class="trip-search__item">
        <select data-trip-search-select-country></select>
    </div>
    <div class="trip-search__item">
        <select data-trip-search-select-camp></select>
    </div>
    <div class="trip-search__item">
        <select data-trip-search-select-type></select>
    </div>
    <div class="trip-search__item">
        <select data-trip-search-select-transfer></select>
    </div>
    <div class="trip-search__item">
        <select data-trip-search-select-count-nights></select>
    </div>

    <div class="trip-search__item">
        <div class="placeholder">
            <input class="input placeholder__input input-mask" data-mask="00.00.0000" placeholder="{!! $title !!}"
                   data-datepicker-input
                   data-datepicker-max-date="minus18y"
                   data-datepicker-start-view="year"
            >
            <div class="placeholder__item"><?= _e('Select date', 'usb-travel'); ?></div>
        </div>
    </div>

    <div class="trip-search__item"></div>
    <div class="trip-search__button">
        <?= _e('Search', 'usb-travel'); ?>
    </div>


</div>
