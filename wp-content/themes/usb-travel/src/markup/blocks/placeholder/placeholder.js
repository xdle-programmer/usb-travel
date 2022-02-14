let placeholders = Array.from(document.querySelectorAll('.placeholder'));

if (placeholders.length > 0) {
    for (let $placeholder of placeholders) {
        checkEmptyInput($placeholder);
    }
}

export function checkEmptyInput($wrapper) {
    let not_empty_class = 'placeholder--not-empty';
    let empty_class = 'placeholder--empty';
    let $placeholder;
    let $placeholder_item;

    let $input = $wrapper.querySelector('.placeholder__input');

    function checkVal() {
        $placeholder = $input.closest('.placeholder');
        $placeholder_item = $placeholder.querySelector('.placeholder__item');
        if ($input.value.length > 0) {
            $placeholder.classList.add(not_empty_class);
            $placeholder.classList.remove(empty_class);
        } else {
            $placeholder.classList.remove(not_empty_class);
            $placeholder.classList.add(empty_class);
        }
    }

    $input.addEventListener('input', checkVal);
    $input.addEventListener('change', checkVal);

    checkVal();
}
