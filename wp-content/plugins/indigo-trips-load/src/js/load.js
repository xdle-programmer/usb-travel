import XLSX from 'xlsx/dist/xlsx.full.min.js';

function excelDateToJSDate(date) {
    return new Date(Math.round((date - 25569)*86400*1000));
}

export function loadFile(options) {
    const {$input} = options;
    const {$wrapper} = options;
    const reader = new FileReader();

    init();

    function init() {
        readInit();

        addEventListener();
    }

    function addEventListener() {

        $input.addEventListener('change', event => {
            let file = event.target.files[0];

            if (!file) {
                creatHtmlTable(null);
            } else {
                reader.readAsBinaryString(file);
            }
        });

    }

    function readInit() {
        reader.onload = (event) => {
            const data = event.target.result;

            let workbook = XLSX.read(data, {
                type: 'binary'
            });

            workbook.SheetNames.forEach(function (sheetName) {
                let objectFromXLSX = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);

                creatHtmlTable(objectFromXLSX);
            });
        };

        reader.onerror = (error) => {
            console.error(error);
        };
    }

    function creatHtmlTable(tripArray) {
        if (!tripArray) {
            $wrapper.innerHTML = '';
            return;
        }

        const dateKey = 'Выезд';
        const ageTypes = ['Взрослые', 'Дети'];
        const countDaysRow = 0;
        const countNightsRow = 2;
        const ageRow = 3;
        const startValuesRow = 4;

        // Готовим массив для типов поездок
        let tripsTypes = [];

        // Разбираем первую строку массива для формирования типов поездок
        for (let key in tripArray[0]) {
            if (key === dateKey) {
                continue;
            }

            let tripType = {
                countDays: tripArray[countDaysRow][key],
                countNights: tripArray[countNightsRow][key],
                age: ageTypes.indexOf(tripArray[ageRow][key]),
                key
            };

            tripsTypes.push(tripType);
        }

        // Готовим массив для поездок
        let trips = [];

        // Разбираем массив, начиная со стартовой строки
        for (let index = startValuesRow; index < tripArray.length; index++) {
            for (let tripType of tripsTypes) {

                let trip = {...tripType}

                if (isNaN(parseInt(tripArray[index][trip.key]))) {
                    continue;
                }

                trip.date = excelDateToJSDate(tripArray[index][dateKey])
                trip.price = tripArray[index][trip.key]

                trips.push(trip)
            }
        }


        console.log(trips);
        for (let trip of trips) {
            console.log(trip)
        }


        // Формируем количество столбцов

    }
}
