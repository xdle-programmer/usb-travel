export class Select {

    /* Example options
    {
        selectClass:'select',     // String
        $select:document.getElementsByClassName(this.selectClass)[0],       // node element
        customSelectClass:'custom-select',     // String
    }
     */

    constructor(options) {
        this.selectClass = options && options.selectClass ?
            options.selectClass :
            'select';
        this.$select = options && options.$select ?
            options.$select :
            document.getElementsByClassName(this.selectClass)[0];
        this.customSelectClass = options && options.customSelectClass ?
            options.customSelectClass :
            'custom-select';
        this.openClass = options && options.openClass ?
            options.openClass :
            this.customSelectClass + '--open';
        this.selectedClass = options && options.selectedClass ?
            options.selectedClass :
            this.customSelectClass + '--selected';
        this.itemClass = options && options.itemClass ?
            options.itemClass :
            this.customSelectClass + '__item';
        this.selectedItemClass = options && options.selectedItemClass ?
            options.selectedItemClass :
            this.itemClass + '--selected';
        this.disabledItemClass = options && options.disabledItemClass ?
            options.disabledItemClass :
            this.itemClass + '--disabled';
        this.$customSelect = document.createElement('div');
        this.current = null;
        this.options = Array.from(this.$select.getElementsByTagName('option'));
        this.changeEvent = new Event('change', {bubbles: true});
        this.data = this.#getSelectData();
        this.init();
    }

    init() {
        this.$select.parentElement.appendChild(this.$customSelect).tabIndex = 0;
        this.$customSelect.classList.add(this.customSelectClass);
        this.$customSelect.appendChild(this.$select);
        this.#setHtml();
        this.setKeyboardManage();
    }

    #getSelectData() {
        this.options = Array.from(this.$select.getElementsByTagName('option'));
        let optionsArray = new Map();

        this.options.map((item, index) => {
            let value = item.hasAttribute('value') ?
                item.getAttribute('value') :
                item.innerText;

            let selected = false;

            if (item.hasAttribute('selected')) {
                this.current = index;
                selected = true;
            }

            const option = {
                name: item.innerText,
                selected: item.hasAttribute('selected'),
                disabled: item.hasAttribute('disabled'),
                hidden: item.hasAttribute('hidden'),
                index: index
            };

            optionsArray.set('index' + option.index, option);
        });

        if (this.current === null) {
            this.current = optionsArray.get('index' + 0).index;
        }
        return optionsArray;
    }

    #setHtml() {
        const template = `
            <div data-select-type="button" class="${this.customSelectClass}__input">
                <div data-select-type="button" class="${this.customSelectClass}__input-name">${this.data.get('index' + this.current).name}</div>
                <div data-select-type="button" class="${this.customSelectClass}__input-icon"></div>
            </div>
            <div data-select-type="button" class="${this.customSelectClass}__list"></div>
        `;

        this.$customSelect.insertAdjacentHTML('beforeEnd', template);

        this.#setListHtml();
        this.#setup();
    }

    #setListHtml() {
        let $list = this.$customSelect.querySelector(`.${this.customSelectClass}__list`);
        let list = '';

        for (let item of this.data.values()) {
            if (!item.hidden) {
                let classDisabled = '';
                let classSelected = '';

                if (item.selected) {
                    classSelected = this.selectedItemClass;
                }

                if (item.disabled) {
                    classDisabled = this.disabledItemClass;
                }

                list += `<div data-select-index="${item.index}" tabindex="0" data-select-type="value" class="${this.customSelectClass}__item ${classSelected} ${classDisabled}">${item.name}</div>`;
            }
        }

        $list.innerHTML = '';
        $list.insertAdjacentHTML('beforeEnd', list);
    }

    #setup() {
        this.clickHandler = this.clickHandler.bind(this);
        this.changeHandler = this.changeHandler.bind(this);
        document.addEventListener('click', this.clickHandler);
        this.$select.addEventListener('change', this.changeHandler);
        this.$name = this.$customSelect.querySelector('.' + this.customSelectClass + '__input-name');
        this.$items = this.$customSelect.querySelectorAll('.' + this.customSelectClass + '__item');
    }

    clickHandler(event) {
        const isSelectClick = (click) => {
            if (click === this.$customSelect) {
                return true;
            } else if (click.closest('.' + this.customSelectClass) && click.closest('.' + this.customSelectClass) === this.$customSelect) {
                return true;
            } else {
                return false;
            }
        };

        if (isSelectClick(event.target)) {
            const type = event.target.dataset.selectType;
            if (type === 'button') {
                this.toggle();
            } else if (type === 'value') {
                if (!event.target.classList.contains(this.disabledItemClass)) {
                    this.current = event.target.dataset.selectIndex;
                    this.select(this.current);
                    this.close();
                }
            }
        } else {
            this.close();
        }
    }

    changeHandler(event) {
        this.options = Array.from(this.$select.getElementsByTagName('option'));

        this.options.map((item, index) => {
            let value;

            if (item.hasAttribute('value')) {
                value = item.getAttribute('value');
            } else {
                value = item.innerText;
            }

            if (value === this.$select.value) {
                this.#setSelectHtml(index);
            }
        });

        if (this.$select.value === 'default') {
            this.$customSelect.classList.remove(this.selectedClass);
        } else {
            this.$customSelect.classList.add(this.selectedClass);
        }
    }

    select(index) {
        this.current = index;
        this.$select.value = this.options[index].value;
        this.$select.dispatchEvent(this.changeEvent);
    }

    #setSelectHtml(index) {
        this.$name.innerText = this.data.get('index' + index).name;

        this.$items.forEach(($item) => {
            if (parseInt($item.dataset.selectIndex) === index && this.$select.value !== 'default') {
                $item.classList.add(this.selectedItemClass);
            } else {
                $item.classList.remove(this.selectedItemClass);
            }
        });

        if (this.$select.value === 'default') {
            this.$customSelect.classList.remove(this.selectedClass);
        } else {
            this.$customSelect.classList.add(this.selectedClass);
        }
    }

    refresh() {
        this.data = this.#getSelectData();
        this.#setListHtml();
        this.select(this.current);

        console.log(this.current)
        console.log(this.$select.value)
    }

    toggle() {
        if (this.$customSelect.classList.contains(this.openClass)) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        this.$customSelect.classList.add(this.openClass);
    }

    close() {
        this.$customSelect.classList.remove(this.openClass);
    }

    setKeyboardManage() {
        const down = 'ArrowDown';
        const up = 'ArrowUp';
        const space = 'Space';
        const enter = 'Enter';
        const escape = 'Escape';
        const specialKeys = [
            down,
            up,
            space,
            enter,
            escape,
        ];

        this.$customSelect.addEventListener('keydown', event => {
            const isOpen = this.$customSelect.classList.contains(this.openClass);

            if (specialKeys.indexOf(event.code) !== -1) {
                event.preventDefault();
                const $list = Array.from(this.$customSelect.querySelectorAll(`.${this.itemClass}`));

                if (!isOpen && event.code === enter) {
                    return;
                }

                if (!isOpen) {
                    if (event.code === space) {
                        this.open();
                        return;
                    }

                    if (event.code === down || event.code === up) {
                        this.open();

                        if (event.code === down) {
                            $list[0].focus();
                        }

                        if (event.code === up) {
                            $list[$list.length - 1].focus();
                        }

                        return;
                    }

                    return;
                }

                if (isOpen) {
                    const currentIndex = $list.indexOf(document.activeElement);

                    if (event.code === escape) {
                        this.close();
                        this.$customSelect.focus();
                    }

                    if (event.code === down) {
                        const nextIndex = currentIndex + 1 === $list.length ?
                            0 :
                            currentIndex + 1;
                        $list[nextIndex].focus();
                    }

                    if (event.code === up) {
                        const nextIndex = currentIndex - 1 < 0 ?
                            $list.length - 1 :
                            currentIndex - 1;
                        $list[nextIndex].focus();
                    }

                    if (event.code === space || event.code === enter) {
                        if (document.activeElement.classList.contains(this.itemClass)) {
                            this.select(document.activeElement.dataset.selectIndex);
                            this.close();
                            this.$customSelect.focus();
                        }
                    }
                }
            }

        });

    }
}

export function createSelect(options) {
    const {$select} = options;
    const {placeholder} = options;
    let {values} = options;

    setHtml();

    function setHtml(selectedValue) {
        $select.innerHTML = '';

        let selectedValueHtml = 'selected';
        let defaultSelected = '';

        if (!selectedValue) {
            defaultSelected = selectedValueHtml;
        }

        let optionsHtml = `<option value="default" ${defaultSelected}>${placeholder}</option>`;

        for (let value of values) {
            let selectedHtml = '';

            if (selectedValue && selectedValue === value.value) {
                selectedHtml = selectedValueHtml;
            }

            optionsHtml += `<option ${selectedHtml} value="${value.value}">${value.name}</option>`;
        }

        $select.insertAdjacentHTML('beforeend', optionsHtml);
    }

    const select = new Select({
        $select: $select,
    });

    this.refresh = (newValues) => {
        values = newValues;
        let oldValue = $select.value;
        let oldName = $select.options[$select.selectedIndex].innerText;

        let newSelectedValue;

        if (oldValue !== 'default') {
            newSelectedValue = newValues.filter((item) => {
                return item.value.toString() === oldValue.toString();
            });
        }

        console.log(oldValue);

        if (newSelectedValue) {
            if (newSelectedValue.length > 0) {

                if (newSelectedValue[0].name === oldName) {
                    setHtml(newSelectedValue[0].value);
                } else {
                    setHtml();
                }
            }
        }

        select.refresh();
    };
}
