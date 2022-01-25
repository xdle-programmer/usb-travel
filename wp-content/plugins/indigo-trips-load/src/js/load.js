import XLSX from 'xlsx/dist/xlsx.full.min.js';
import {Select} from '../custom-select/custom-select';
import {formatDate, moment} from '../index';

function excelDateToJSDate(date) {
    return new Date(Math.round((date - 25569) * 86400 * 1000));
}

export function loadFile($wrapper) {
    const reader = new FileReader();

    const classPrefix = 'trip-load';
    const classHidden = `${classPrefix}__hidden`;
    const classLoading = `${classPrefix}--loading`;

    const classRow = `${classPrefix}__row`
    const classCell = `${classPrefix}__cell`
    const classCellInner = `${classPrefix}__cell-inner`
    const classCellHeader = `${classPrefix}__cell--header`

    const defaultValue = `default`;
    const dateKey = 'Выезд';
    const ageTypes = ['Взрослые', 'Дети'];

    const $header = $wrapper.querySelector('#trip-load-header');
    const $controls = $wrapper.querySelector('#trip-load-controls');
    const $tableWrapper = $wrapper.querySelector('#trip-load-table-check-wrapper');
    const $campSelect = $wrapper.querySelector('#trip-load-camp-select');
    const $typeSelect = $wrapper.querySelector('#trip-load-type-select');
    const $inputWrapper = $wrapper.querySelector('#trip-load-file-input-wrapper');
    const $input = $wrapper.querySelector('#trip-load-file-input');
    const $table = $wrapper.querySelector('#trip-load-table-check');
    const $error = $wrapper.querySelector('#trip-load-error');


    init();

    function init() {
        readerInit();

        addEventListener();

        const campSelect = new Select({
            $select: $campSelect,
        });

        const typeSelect = new Select({
            $select: $typeSelect,
        });

        $header.classList.remove(classHidden);
        $controls.classList.remove(classHidden);
        $tableWrapper.classList.remove(classHidden);
        $wrapper.classList.remove(classLoading);
    }

    function addEventListener() {

        $input.addEventListener('change', event => {
            let file = event.target.files[0];

            $error.classList.add(classHidden);

            if (!file) {
                createTable(null);
            } else {

                const nameArray = file.name.split('.');

                if (nameArray.length === 0 || nameArray[nameArray.length - 1] !== 'xlsx') {
                    createTable(null);
                    $error.classList.remove(classHidden);
                    return;
                }

                reader.readAsBinaryString(file);
            }
        });

        $campSelect.addEventListener('change', () => {
            checkShowFileInput();
        });

        $typeSelect.addEventListener('change', () => {
            checkShowFileInput();
        });

        $inputWrapper.addEventListener('click', () => {
            $input.click();
        });
    }

    function checkShowFileInput() {
        $error.classList.add(classHidden);

        if ($typeSelect.value !== defaultValue && $campSelect.value !== defaultValue) {
            $inputWrapper.classList.remove(classHidden);
        } else {
            $inputWrapper.classList.add(classHidden);
            createTable(null);
        }
    }

    function readerInit() {
        reader.onload = (event) => {
            const data = event.target.result;

            let workbook = XLSX.read(data, {
                type: 'binary'
            });


            workbook.SheetNames.forEach(function (sheetName) {
                let objectFromXLSX = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);

                createTable(createTripsArray(objectFromXLSX));
            });
        };

        reader.onerror = (error) => {
            console.error(error);
        };
    }

    function createTripsArray(rowsArray) {
        const countDaysRow = 0;
        const countNightsRow = 2;
        const ageRow = 3;
        const startValuesRow = 4;

        // Готовим массив для типов поездок
        let tripsTypes = [];

        // Разбираем первую строку массива для формирования типов поездок
        for (let key in rowsArray[0]) {
            if (key === dateKey) {
                continue;
            }

            let tripType = {
                countDays: rowsArray[countDaysRow][key],
                countNights: rowsArray[countNightsRow][key],
                age: ageTypes.indexOf(rowsArray[ageRow][key]),
                key
            };

            tripsTypes.push(tripType);
        }

        // Готовим массив для поездок
        let trips = [];

        // Разбираем массив, начиная со стартовой строки
        for (let index = startValuesRow; index < rowsArray.length; index++) {
            for (let tripType of tripsTypes) {

                let trip = {...tripType};

                if (isNaN(parseInt(rowsArray[index][trip.key]))) {
                    continue;
                }

                trip.date = excelDateToJSDate(rowsArray[index][dateKey]);
                trip.price = rowsArray[index][trip.key];

                trips.push(trip);
            }
        }

        let sortTrips = trips.sort((a, b) => {
            return a.countNights - b.countNights;
        });

        return sortTrips;
    }

    function createTable(tripsArray) {
        if (!tripsArray) {
            $table.innerHTML = '';
            return;
        }

        const uniqueDates = new Set();
        const uniqueTypeTrip = new Set();
        const uniqueTypeTripObjects = [];

        for (let trip of tripsArray) {
            uniqueDates.add(+trip.date);

            let typeKey = `countDays${trip.countDays}countNights${trip.countNights}age${trip.age}`;

            if (!uniqueTypeTrip.has(typeKey)) {
                uniqueTypeTrip.add(typeKey);

                uniqueTypeTripObjects.push({
                    countDays: trip.countDays,
                    countNights: trip.countNights,
                    age: trip.age,
                    key: typeKey
                });
            }
        }

        const countColStyle = `100px repeat(${uniqueTypeTripObjects.length},1fr)`;

        createHeader();

        createBody();

        function createHeader() {
            let $emptyCell = document.createElement('div');
             $emptyCell.classList.add(classCell);
             $emptyCell.classList.add(classCellHeader);
            let $header = document.createElement('div');
            $header.classList.add(classRow);
            $header.style.gridTemplateColumns = countColStyle;
            $header.appendChild($emptyCell);

            uniqueTypeTripObjects.forEach((trip) => {
                let $cell = document.createElement('div');
                $cell.classList.add(classCell);
                $cell.classList.add(classCellHeader);

                let $cellCount = document.createElement('div');
                $cellCount.classList.add(classCellInner);
                $cellCount.innerText = `${trip.countDays} д./${trip.countNights} н.`;
                $cell.appendChild($cellCount);

                let $cellType = document.createElement('div');
                $cellType.classList.add(classCellInner);
                $cellType.innerText = ageTypes[trip.age];
                $cell.appendChild($cellType);

                $header.appendChild($cell);
            });

            $table.insertAdjacentElement('afterbegin', $header);
        }

        function createBody() {
            uniqueDates.forEach((date) => {
                let $row = document.createElement('div');
                $row.classList.add(classRow);
                $row.style.gridTemplateColumns = countColStyle;
                let $dateCell = document.createElement('div');
                $dateCell.classList.add(classCell);
                $dateCell.innerText = formatDate(date);

                $row.appendChild($dateCell);

                uniqueTypeTripObjects.forEach((trip) => {
                    let $cell = document.createElement('div');
                    $cell.classList.add(classCell);
                    $cell.dataset.cellKey = `${date}${trip.key}`;
                    $row.appendChild($cell);
                });

                $table.insertAdjacentElement('beforeend', $row);
            });

            for (let trip of tripsArray) {
                let dataSelector = `${+trip.date}countDays${trip.countDays}countNights${trip.countNights}age${trip.age}`;
                $table.querySelector(`[data-cell-key="${dataSelector}"]`).innerText = trip.price;
            }
        }
    }
}
