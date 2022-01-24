import XLSX from 'xlsx/dist/xlsx.full.min.js';

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

    function creatHtmlTable(tripObject) {
        if (!tripObject) {
            $wrapper.innerHTML = '';
            return;
        }

        console.log(tripObject);

        // Формируем количество столбцов

    }
}
