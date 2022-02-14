import { Datepicker } from 'vanillajs-datepicker';

Object.assign(Datepicker.locales, ru);

import ru from 'vanillajs-datepicker/locales/ru';

export let DatepickerRu = Datepicker;

export const axios = require('axios');
export const moment = require('moment');
export const defaultDateFormat = 'DD.MM.YYYY';
export const defaultDateFormatWithTime = 'DD.MM.YYYY HH:mm';
export const defaultDateFormatWithExactTime = 'DD.MM.YYYY HH:mm:ss';
export const dbDateFormat = 'YYYY-MM-DD';

if (document.querySelector('meta[name="csrf-token"]')) {
    axios.defaults.headers.common['Content-Type'] = 'application/json';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')
        .getAttribute('content');
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.withCredentials = true;
}

export function formatDate(date) {
    if (!date) {
        return '';
    }

    return moment(date)
        .format(defaultDateFormat);
}

export function formatDateWithTime(date) {
    return moment(date)
        .format(defaultDateFormatWithTime);
}

export function formatDateWithExactTime(date) {
    return moment(date)
        .format(defaultDateFormatWithExactTime);
}

export function formatDbDate(date) {
    return moment(date, defaultDateFormat)
        .format(dbDateFormat);
}

export function monthCalc(date, monthCount) {
    return formatDbDate(new Date(moment(date)
        .add(monthCount, 'M')
        .subtract(1, 'day')
        .format(dbDateFormat)));
}
