<div class="trip-load trip-load--loading">

    <div class="trip-load__header trip-load__hidden" id="trip-load-header">
        <div class="trip-load__title">Загрузка поездок из файла</div>
        <a href="#" class="trip-load__example">
            <img class="trip-load__example-icon" src="<?= plugin_dir_url(__DIR__) ?>/asset/xlsx-icon.svg">
            <div class="trip-load__example-text">Скачать образец таблицы</div>
        </a>
    </div>

    <div class="trip-load__controls trip-load__hidden" id="trip-load-controls">
        <div class="trip-load__controls-item">
            <!--Выбор лагерей-->
            <select id="trip-load-camp-select">
                <option value="default">Выберите лагерь</option>
                <?php createSelectOptions(getCampsValues()); ?>
            </select>
        </div>

        <div class="trip-load__controls-item">
            <!--Выбор типов поездок-->
            <select id="trip-load-type-select">
                <option value="default">Выберите тип поездки</option>
                <?php createSelectOptions(getTripsTypesValues()); ?>
            </select>
        </div>

        <div class="trip-load__controls-item trip-load__hidden" id="trip-load-file-input-wrapper">
            <div class="trip-load__load-file-button">
                <div class="trip-load__load-file-button-text">Выберите файл</div>
                <input type="file" class="trip-load__load-file-input" id="trip-load-file-input">
            </div>
        </div>
    </div>

    <div class="trip-load__error trip-load__hidden" id="trip-load-error">
        Ошибка чтения файла
    </div>

    <div class="trip-load__table-wrapper trip-load__hidden" id="trip-load-table-check-wrapper">
        <div class="trip-load__table-wrapper" id="trip-load-table-check"></div>
    </div>
</div>










