import IMask from 'imask';

window.inputsMask = new createInputsMask();

function createInputsMask() {
    const inputsMask = Array.from(document.querySelectorAll('.input-mask'));

    if (inputsMask.length > 0) {
        for (let $input of inputsMask) {
            initInputMask($input);
        }
    }
}

export function initInputMask($input) {
    let maskOptions = {
        mask: $input.dataset.mask
    };

    if ($input.dataset.mask === 'number') {
        maskOptions.mask = Number;
        maskOptions.min = parseInt($input.dataset.maskMin);
        maskOptions.max = parseInt($input.dataset.maskMax);
    }

    let mask = IMask($input, maskOptions);

    $input.addEventListener('updateMask', () => {
        mask.updateValue();
    });

}
