import {loadFile} from './js/load';

document.addEventListener('DOMContentLoaded', () => {
    const $wrapper = document.querySelector('.trip-load');

    if ($wrapper) {
        loadFile($wrapper);
    }
});



export const moment = require('moment');
export const defaultDateFormat = 'DD.MM.YYYY';
export const dbDateFormat = 'YYYY-MM-DD';



export function formatDate(date) {
    if (!date) {
        return '';
    }

    return moment(date)
        .format(defaultDateFormat);
}

export function formatDbDate(date) {
    return moment(date, defaultDateFormat)
        .format(dbDateFormat);
}
