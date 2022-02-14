import { moment, DatepickerRu } from '../../../index';
import { formatDate } from '../../../index';

let datePresets = {
    today: formatDate(new Date()),
    minus18y: formatDate(moment(new Date())
        .subtract(18, 'year')),
    minus100y: formatDate(moment(new Date())
        .subtract(100, 'year')),
    plus30y: formatDate(moment(new Date())
        .add(30, 'year')),
    tomorrow: formatDate(moment(new Date())
        .add(1, 'day')),
    plus2m: formatDate(moment(new Date())
        .add(2, 'month')),
    plus1y: formatDate(moment(new Date())
        .add(1, 'year')),
    plus2mminus1day: formatDate(moment(new Date())
        .add(2, 'month')
        .subtract(1, 'day')),
};

const datepickerInputsArray = Array.from(document.querySelectorAll('[data-datepicker-input]'));

if (datepickerInputsArray.length > 0) {

    for (let $datepicker of datepickerInputsArray) {
        initDatepicker($datepicker);
    }
}

export function initDatepicker($datepicker) {
    let options = {
        format: 'dd.mm.yyyy',
        language: 'ru',
        autohide: true,
        updateOnBlur: true,
    };

    if ($datepicker.dataset.datepickerMinDate) {
        options.minDate = datePresets[$datepicker.dataset.datepickerMinDate];
    } else {
        options.minDate = datePresets['minus100y'];
    }

    if ($datepicker.dataset.datepickerMaxDate) {
        options.maxDate = datePresets[$datepicker.dataset.datepickerMaxDate];
    } else {
        options.maxDate = formatDate(moment(new Date())
            .subtract(2, 'month'));
    }

    if ($datepicker.dataset.datepickerStartView === 'year') {
        options.startView = 2;
    } else if ($datepicker.dataset.datepickerStartView === 'month') {
        options.startView = 1;
    }

    const datepicker = new DatepickerRu($datepicker, options);
    $datepicker.addEventListener('changeDate', () => {
        $datepicker.dispatchEvent(new Event('change', { bubbles: true }));
    });

    $datepicker.addEventListener('input', event => {
        datepicker.hide();
        // datepicker.update();
    });

    $datepicker.addEventListener('change', event => {
        if (datepicker.getDate() && $datepicker.value !== datepicker.getDate()) {
            $datepicker.value = formatDate(datepicker.getDate());
        } else {
            $datepicker.value = '';
        }
    });

    $datepicker.addEventListener('datepicker-update', event => {
        datepicker.update();
    });

    $datepicker.addEventListener('datepicker-refresh', event => {
        datepicker.refresh();
    });

    $datepicker.addEventListener('clear-date', event => {
        $datepicker.value = '';
        datepicker.update();
    });

    $datepicker.addEventListener('change-options', event => {
        datepicker.setOptions({
            minDate: event.detail.minDate,
            maxDate: event.detail.maxDate
        });
    });
}
