<div class="trip-search">

    <div class="trip-search__item">
        <select style="display: none" data-trip-search-select-country></select>
    </div>
    <div class="trip-search__item">
        <select style="display: none" data-trip-search-select-camp></select>
    </div>
    <div class="trip-search__item">
        <select style="display: none" data-trip-search-select-type></select>
    </div>
    <div class="trip-search__item">
        <select style="display: none" data-trip-search-select-transfer></select>
    </div>
    <div class="trip-search__item">
        <select style="display: none" data-trip-search-select-count-nights></select>
    </div>

    <div class="trip-search__item">
        <div class="placeholder">
            <input class="input placeholder__input input-mask" data-mask="00.00.0000" placeholder="<?= _e('Select date', 'usb-travel'); ?>"
                   data-datepicker-input
            >
            <div class="placeholder__item"><?= _e('Select date', 'usb-travel'); ?></div>
        </div>
    </div>

    <div class="trip-search__item"></div>
    <button class="trip-search__button">
        <?= _e('Search', 'usb-travel'); ?>
    </button>


</div>
