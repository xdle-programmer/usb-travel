import {createSelect} from "../custom-select/custom-select";
import {formatDate, formatDbDate} from "../../../index";


const $tripSearch = document.querySelector('.trip-search');
if ($tripSearch) {
    tripSearch($tripSearch);
}

console.log($tripSearch);

export function tripSearch($wrapper) {
    console.log(window.searchOptions);

    const classPrefix = 'trip-search';
    const dataPrefix = 'data-trip-search';

    const defaultValue = 'default';

    const $selectCountry = $wrapper.querySelector(`[${dataPrefix}-select-country]`);
    const $selectCamp = $wrapper.querySelector(`[${dataPrefix}-select-camp]`);
    const $selectType = $wrapper.querySelector(`[${dataPrefix}-select-type]`);
    const $selectTransfer = $wrapper.querySelector(`[${dataPrefix}-select-transfer]`);
    const $selectCountNights = $wrapper.querySelector(`[${dataPrefix}-select-count-nights]`);
    const $inputDate = $wrapper.querySelector(`[${dataPrefix}-date]`);
    const $searchButton = $wrapper.querySelector(`[${dataPrefix}-button]`);
    const $clearButton = $wrapper.querySelector(`[${dataPrefix}-clear]`);

    let selectCountry;
    let selectCamp;
    let selectType;
    let selectTransfer;
    let selectCountNights;

    let countries = [];
    let camps = [];
    let types = [];
    let transfer = [];
    let countNights = [];

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
            values: transfer
        });

        selectCountNights = new createSelect({
            $select: $selectCountNights,
            placeholder: window.stringTranslation.selectCountNightsPlaceholder,
            values: countNights
        });

        addListener();

        setInitInputs()
    }

    function addListener() {
        $selectCountry.addEventListener('change', () => {
            if ($selectCountry.value === defaultValue || $selectCamp.value === defaultValue) {
                return;
            }

            const campCountry = window.searchOptions.camps[$selectCamp.value][2];

            if (+$selectCountry.value !== +campCountry) {
                $selectCamp.value = defaultValue;
                $selectCamp.dispatchEvent(new Event('change'));
            }
        });

        $selectCamp.addEventListener('change', () => {
            if ($selectCountry.value === defaultValue || $selectCamp.value === defaultValue) {
                return;
            }

            const campCountry = window.searchOptions.camps[$selectCamp.value][2];

            if (+$selectCountry.value !== +campCountry) {
                $selectCountry.value = defaultValue;
                $selectCountry.dispatchEvent(new Event('change'));
            }
        });

        $searchButton.addEventListener('click', () => {

            const params = [];

            if ($selectCountry.value !== defaultValue) {
                params.push(`search_country=${$selectCountry.value}`);
            }
            if ($selectCamp.value !== defaultValue) {
                params.push(`search_camp=${$selectCamp.value}`);
            }
            if ($selectType.value !== defaultValue) {
                params.push(`number_people=${$selectType.value}`);
            }
            if ($selectTransfer.value !== defaultValue) {
                params.push(`transfer=${$selectTransfer.value}`);
            }
            if ($selectCountNights.value !== defaultValue) {
                params.push(`count_nights=${$selectCountNights.value}`);
            }

            if ($inputDate.value !== '') {
                params.push(`trip_date=${+new Date(formatDbDate($inputDate.value))}`);
            }

            window.location.href = `/trips/?${params.join('&')}`;
        });

        $clearButton.addEventListener('click',()=>{
            window.location.href = `/trips`;
        })
    }

    function setInitInputs() {

        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);

        for(let key of params.keys()) {
            if (key === 'search_country') {
                $selectCountry.value = params.get(key);
                $selectCountry.dispatchEvent(new Event('change'))
            }

            if (key === 'search_camp') {
                $selectCamp.value = params.get(key);
                $selectCamp.dispatchEvent(new Event('change'))
            }

            if (key === 'number_people') {
                $selectType.value = params.get(key);
                $selectType.dispatchEvent(new Event('change'))
            }

            if (key === 'transfer') {
                $selectTransfer.value = params.get(key);
                $selectTransfer.dispatchEvent(new Event('change'))
            }

            if (key === 'count_nights') {
                $selectCountNights.value = params.get(key);
                $selectCountNights.dispatchEvent(new Event('change'))
            }

            if (key === 'trip_date') {
                $inputDate.value = formatDate(new Date(+params.get(key)));
                $inputDate.dispatchEvent(new Event('change'))
            }
        }

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
                name: window.searchOptions.camps[key][0] + ' (' + window.searchOptions.camps[key][1] + ')',
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

        for (let key in window.searchOptions.countNights) {

            countNights.push({
                name: window.searchOptions.countNights[key][0],
                value: key
            });
        }
    }

    function refreshSelectValue($select, valuesArray) {

    }

}
