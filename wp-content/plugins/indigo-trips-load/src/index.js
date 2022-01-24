import {loadFile} from './js/load';

document.addEventListener('DOMContentLoaded', () => {
    const $input = document.getElementById('trip-upload');
    const $wrapper = document.getElementById('trip-upload-table-check');

    loadFile({$input, $wrapper});
});
