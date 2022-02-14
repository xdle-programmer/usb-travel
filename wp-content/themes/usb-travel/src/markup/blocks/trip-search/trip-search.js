import {createSelect} from "../custom-select/custom-select";


const $tripSearch = document.querySelector('.trip-search');
if ($tripSearch) {
    tripSearch($tripSearch);
}

console.log($tripSearch);

export function tripSearch($wrapper) {
    console.log(window.searchOptions);

    const classPrefix = 'trip-search';
    const dataPrefix = 'data-trip-search';

    const $selectCountry = $wrapper.querySelector(`[${dataPrefix}-select-country]`);
    const $selectCamp = $wrapper.querySelector(`[${dataPrefix}-select-camp]`);
    const $selectType = $wrapper.querySelector(`[${dataPrefix}-select-type]`);
    const $selectTransfer = $wrapper.querySelector(`[${dataPrefix}-select-transfer]`);
    const $selectCountNights = $wrapper.querySelector(`[${dataPrefix}-select-count-nights]`);

    let selectCountry;
    let selectCamp;
    let selectType;
    let selectTransfer;
    let selectCountNights;

    let countries = [];
    let camps = [];
    let types = [];
    let transfer = [];

    init();

    function init() {

        createArrays();

        selectCountry = new createSelect({
            $select: $selectCountry,
            placeholder: window.stringTranslation.selectCountryPlaceholder,
            values: countries
        });

        selectCamp = new createSelect({
            $select: $selectCamp,
            placeholder: window.stringTranslation.selectCampPlaceholder,
            values: camps
        });

        selectType = new createSelect({
            $select: $selectType,
            placeholder: window.stringTranslation.selectGroupPlaceholder,
            values: types
        });

        selectTransfer = new createSelect({
            $select: $selectTransfer,
            placeholder: window.stringTranslation.selectTransferPlaceholder,
            values: countries
        });

        selectCountNights = new createSelect({
            $select: $selectCountNights,
            placeholder: window.stringTranslation.selectCountNightsPlaceholder,
            values: transfer
        });

        addListener();
    }

    function addListener() {

    }

    function createArrays() {
        for (let key in window.searchOptions.countries) {

            countries.push({
                name: window.searchOptions.countries[key][0],
                value: key
            });
        }

        for (let key in window.searchOptions.camps) {

            camps.push({
                name: window.searchOptions.camps[key][0],
                country: window.searchOptions.camps[key][1],
                value: key,
            });
        }

        for (let key in window.searchOptions.groupsType) {

            types.push({
                name: window.searchOptions.groupsType[key][0],
                value: key
            });
        }

        for (let key in window.searchOptions.transportType) {

            transfer.push({
                name: window.searchOptions.transportType[key][0],
                value: key
            });
        }
    }

    function refreshSelectValue($select, valuesArray) {

    }

}
