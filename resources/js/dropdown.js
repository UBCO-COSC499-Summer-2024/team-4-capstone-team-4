const Dropdown = (function () {
    let debugging = true;

    const DEFAULT_OPTIONS = {
        title: 'Dropdown',
        preIcon: 'list',
        searchable: false,
        multiple: false,
        external: null,
        regex: 'i',
        debug: false,
        source: null,
        value: null
    };

    class Dropdown extends HTMLElement {
        constructor() {
            super();

            this._value = null;
            this._options = { ...DEFAULT_OPTIONS };
            this._selectedItems = new Set();
            this._searchValue = "";

            this.toggleDropdown = this.toggleDropdown.bind(this);
            this.handleKeyboardNavigation = this.handleKeyboardNavigation.bind(this);
            this.handleOutsideClick = this.handleOutsideClick.bind(this);
            this.handleSearchInput = this.handleSearchInput.bind(this);
            this.handleDropdownItemSelected = this.handleDropdownItemSelected.bind(this);
        }

        connectedCallback() {
            this._getOptionsFromAttributes();

            this.setAttribute('role', 'listbox');
            this.setAttribute('tabindex', '0');
            this.render();

            this.addEventListener('click', this.toggleDropdown);
            this.addEventListener('keydown', this.handleKeyboardNavigation);
            this.addEventListener('dropdown-item-selected', this.handleDropdownItemSelected);
            document.addEventListener('click', this.handleOutsideClick);

            if (this._options.searchable) {
                this.addSearchFunctionality();
            }
            if (this._options.multiple) {
                this.addMultiSelectFunctionality();
            }
            if (this._options.external) {
                this.loadExternalData(this._options.source);
            }

            if (this._options.value) {
                this.setInitialItem();
            }

            this.setActiveItem();
            this.toggleDebugging(this._options.debug);

            this.initializeDropdownContent();
        }

        setInitialItem() {
            this.setSelected(this._options.value);
        }

        disconnectedCallback() {
            this.removeEventListener('click', this.toggleDropdown);
            this.removeEventListener('keydown', this.handleKeyboardNavigation);
            this.removeEventListener('dropdown-item-selected', this.handleDropdownItemSelected);
            document.removeEventListener('click', this.handleOutsideClick);
        }

        handleDropdownItemSelected(e) {
            this.dispatchEvent(new CustomEvent('change', { detail: e.detail }));
        }

        _getOptionsFromAttributes() {
            for (const [key, defaultValue] of Object.entries(DEFAULT_OPTIONS)) {
                this._options[key] = this.hasAttribute(key)
                    ? this._convertAttributeValue(this.getAttribute(key), typeof defaultValue)
                    : defaultValue;
            }
        }

        _convertAttributeValue(value, targetType) {
            switch (targetType) {
                case 'boolean':
                    return value !== 'false';
                case 'number':
                    return Number(value);
                default:
                    return value;
            }
        }

        render() {
            this.className = 'dropdown-element';

            if (this._options.preIcon) {
                // if no pre Icon element
                if (!this.querySelector('.dropdown-pre-icon')) {
                    this.renderPreIcon();
                }
            }

            if (!this.querySelector('.dropdown-title')) {
                this.renderTitle();
            }

            if (!this.querySelector('.dropdown-button')) {
                const dropdownButton = document.createElement('i');
                dropdownButton.className = 'material-symbols-outlined dropdown-button noselect';
                dropdownButton.textContent = 'arrow_drop_down';
                // this.appendChild(dropdownButton); after title
                this.insertBefore(dropdownButton, this.firstChild.nextSibling.nextSibling);
            }

            let dropdownContent = this.querySelector('dropdown-content');
            if (!dropdownContent) {
                dropdownContent = document.createElement('dropdown-content');
                this.appendChild(dropdownContent);
            }
        }

        renderPreIcon() {
            const dropdownPreIcon = document.createElement('span');
            dropdownPreIcon.className = 'material-symbols-outlined dropdown-pre-icon icon noselect';
            dropdownPreIcon.textContent = this._options.preIcon;
            // this.appendChild(dropdownPreIcon); make first child
            this.insertBefore(dropdownPreIcon, this.firstChild);
        }

        renderTitle() {
            const dropdownTitle = document.createElement('span');
            dropdownTitle.className = 'dropdown-title noselect';
            dropdownTitle.textContent = this._options.title;
            // this.appendChild(dropdownTitle); after preIcon
            this.insertBefore(dropdownTitle, this.firstChild.nextSibling);
        }

        addSearchFunctionality() {
            const searchInput = document.createElement('input');
            searchInput.setAttribute('type', 'text');
            searchInput.setAttribute('placeholder', 'Search...');
            searchInput.setAttribute('autocomplete', 'off');
            searchInput.setAttribute('autocorrect', 'off');
            searchInput.setAttribute('autocapitalize', 'off');
            searchInput.setAttribute('spellcheck', 'false');
            searchInput.setAttribute('tabindex', '-1');
            searchInput.className = 'dropdown-search';

            const dropdownContent = this.querySelector('dropdown-content');
            dropdownContent.insertBefore(searchInput, dropdownContent.firstChild);

            searchInput.addEventListener('input', this.handleSearchInput);
        }

        handleSearchInput(e) {
            this._searchValue = e.target.value.trim();

            const regex = this._options.useCustomRegex
                ? new RegExp(this._searchValue, this._options.regex)
                : new RegExp(this._searchValue.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&').split(/\s+/).join('.*'), 'i');

            this.querySelectorAll('dropdown-item').forEach((item) => {
                item.style.display = regex.test(item.textContent) ? 'block' : 'none';
            });

            this.updateBorderRadius();

            if (this._options.multiple) {
                this.updateDropdownTitle();
            }
        }

        updateBorderRadius() {
            // ... (Implementation for rounded corners if needed)
        }

        setSelected(value) {
            if (this._options.multiple) {
                this._selectedItems = new Set(value);
                this.updateSelectedItemsDOM();
            } else {
                this._value = value;
                this.updateSelectedItemDOM();
            }

            this.updateDropdownTitle();
        }

        getSelected() {
            return this._options.multiple ? [...this._selectedItems] : this._value ? [this._value] : [];
        }

        clearSelected() {
            if (this._options.multiple) {
                this._selectedItems.clear();
                this.updateSelectedItemsDOM();
            } else {
                this._value = null;
                this.updateSelectedItemDOM();
            }

            this.updateDropdownTitle();
        }

        updateDropdownTitle() {
            const dropdownTitle = this.querySelector('.dropdown-title');

            if (this._options.multiple) {
                const selectedItemsCount = this._selectedItems.size;
                dropdownTitle.textContent = selectedItemsCount === 0
                    ? this._options.title
                    : `${selectedItemsCount} item${selectedItemsCount > 1 ? 's' : ''} selected`;
            } else {
                const selectedItem = this.querySelector(`dropdown-item[value="${this._value}"]`);
                dropdownTitle.textContent = selectedItem ? selectedItem.textContent : this._options.title;
            }
        }

        addMultiSelectFunctionality() {
            this.addEventListener('keydown', this.handleMultiSelect);
        }

        handleMultiSelect(e) {
            if (e.key === 'Enter' && e.target.tagName.toLowerCase() === 'dropdown-item') {
                const selectedValue = e.target.getAttribute('value');
                console.log('selectedValue');
                if (this._selectedItems.has(selectedValue)) {
                    this._selectedItems.delete(selectedValue);
                } else {
                    this._selectedItems.add(selectedValue);
                }

                this.updateSelectedItemsDOM();
                this.updateDropdownTitle();

                this.dispatchEvent(new CustomEvent('dropdown-item-selected', {
                    detail: { value: selectedValue, selected: this._selectedItems.has(selectedValue) },
                }));
            }
        }

        updateSelectedItemsDOM() {
            this.querySelectorAll('dropdown-item').forEach((item) => {
                if (this._selectedItems.has(item.getAttribute('value'))) {
                    item.setAttribute('active', '');
                } else {
                    item.removeAttribute('active');
                }
            });
        }

        get value() {
            return this.getSelected();
        }

        set value(val) {
            if (this._options.multiple && Array.isArray(val)) {
                this._selectedItems = new Set(val);
                this.updateSelectedItemsDOM();
            } else if (!this._options.multiple) {
                this._value = val ? val[0] : null;
                this.updateSelectedItemDOM();
            }

            this.updateDropdownTitle();
            this.dispatchEvent(new Event('change'));
        }

        updateSelectedItemDOM() {
            this.querySelectorAll('dropdown-item').forEach((item) => {
                if (item.getAttribute('value') === this._value) {
                    item.setAttribute('active', '');
                } else {
                    item.removeAttribute('active');
                }
            });
        }

        async loadExternalData(source) {
            try {
                let data = await this.fetchData(source);
                this.processData(data);
            } catch (error) {
                this.dispatchEvent(new CustomEvent('dropdown-source-error', { detail: error }));
                console.warn(`Error loading external data from ${source}`, error);
            }
        }

        async fetchData(source) {
            try {
                if (typeof source === 'function') {
                    let data = await source(); // Assuming asynchronous function
                    if (!data || typeof data !== 'object') {
                        throw new Error("Dropdown data must be an object");
                    }
                    return data;
                } else if (this.isValidURL(source)) {
                    let response = await fetch(source);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return await response.json(); // Assuming JSON response
                } else {
                    throw new Error("Invalid data source provided");
                }
            } catch (error) {
                throw error; // Re-throw to handle at a higher level
            }
        }

        isValidURL(str) {
            try {
                new URL(str);
                return true;
            } catch (_) {
                return false;
            }
        }

        processData(data) {
            this.dispatchEvent(new CustomEvent('dropdown-source-loaded', { detail: data }));

            for (const [name, value] of Object.entries(data)) {
                this.appendValue(name, value);
            }
        }

        async appendValue(name, value) {
            let dropdownContent = this.querySelector('dropdown-content');
            if (!dropdownContent) {
                dropdownContent = document.createElement('dropdown-content');
                this.appendChild(dropdownContent);
            }
            const existingItem = dropdownContent.querySelector(`dropdown-item[value='${value}']`);
            console.log(existingItem !== null);
            if (existingItem) {
                return;
            }
            const dropdownItem = document.createElement('dropdown-item');
            dropdownItem.className = 'dropdown-item';
            dropdownItem.textContent = name;
            dropdownItem.setAttribute('value', value);

            if (value?.hasOwnProperty('data')) {
                for (const [dataName, dataValue] of Object.entries(value.data)) {
                    dropdownItem.dataset[dataName] = dataValue;
                }
            }

            dropdownContent.appendChild(dropdownItem);
            // remove duplicates
            const values = new Set();
            dropdownContent.querySelectorAll('dropdown-item').forEach((item) => { values.add(item.textContent); });
            await new Promise(resolve => setTimeout(() => resolve(), 0));
        }

        toggleDropdown(e) {
            const dropdownContent = this.querySelector('dropdown-content');
            if (dropdownContent) {
                dropdownContent.classList.toggle('show');
                this.querySelector('.dropdown-button').textContent = dropdownContent.classList.contains('show')
                    ? 'arrow_drop_up'
                    : 'arrow_drop_down';
                e.stopPropagation();
            }
        }

        handleOutsideClick(e) {
            if (!this.contains(e.target)) {
                const dropdownContent = this.querySelector('dropdown-content');
                if (dropdownContent?.classList.contains('show')) {
                    dropdownContent.classList.remove('show');
                    this.querySelector('.dropdown-button').textContent = 'arrow_drop_down';
                }
            }
        }

        setActiveItem() {
            if (this._options.multiple) {
                this.updateSelectedItemsDOM();
            } else {
                this.updateSelectedItemDOM();
            }
        }

        handleKeyboardNavigation(e) {
            const items = Array.from(this.querySelectorAll('dropdown-item:not([style*="display: none"])'));
            const activeItem = this.querySelector('dropdown-item[active]');

            if (!items.length) return;

            const currentIndex = items.indexOf(activeItem);

            let newIndex;
            switch (e.key) {
                case 'ArrowUp':
                    newIndex = (currentIndex - 1 + items.length) % items.length;
                    break;
                case 'ArrowDown':
                    newIndex = (currentIndex + 1) % items.length;
                    break;
                case 'Enter':
                    if (activeItem) {
                        this.selectItem(activeItem);
                    }
                    break;
                default:
                    return;
            }

            if (newIndex !== undefined) {
                items[newIndex].focus();
            }
        }

        selectItem(item) {
            let val;
            if (this._options.multiple === true) {
                this.handleMultiSelect({ target: item, key: "Enter" }); // Simulate Enter key press
                this.setSelected([...this._selectedItems]);
            } else {
                val = item.getAttribute('value');
                this.value = val;
                this.setSelected(val);

                if (this.querySelector('dropdown-content.show')) {
                    this.toggleDropdown({ stopPropagation: () => { } }); // Close dropdown
                }
            }

            // send change event and input event
            this.dispatchEvent(new Event('input'));
            this.dispatchEvent(new Event('change'));
            this.dispatchEvent(new CustomEvent('dropdown-item-selected', {
                detail: {
                    value: this._options.multiple ? [...this._selectedItems] : val,
                },
                bubbles: true // Optional: Allow event to bubble up
            }));
        }

        initializeDropdownContent() {
            const dropdownContent = this.querySelector('dropdown-content');
            if (!dropdownContent) return;

            dropdownContent.addEventListener('click', (e) => {
                if (e.target.tagName.toLowerCase() === 'dropdown-item') {
                    // console.log(e.target);
                    this.selectItem(e.target);
                }
            });

            const values = this.getAttribute('values') || "{}";
            try {
                const parsedValues = JSON.parse(values);
                for (const [name, value] of Object.entries(parsedValues)) {
                    this.appendValue(name, value);
                }
            } catch (error) {
                console.error("Error parsing 'values' attribute:", error);
            }
        }

        toggleDebugging(value) {
            debugging = value !== undefined ? value : !debugging;
            return debugging;
        }

        log(...args) {
            if (this._options.debug) {
                console.log(...args);
            }
        }

        // ... applyDefaultStyles() method remains mostly unchanged ...
        applyDefaultStyles() {
            const style = document.createElement('style');
            const styleContent = `
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    --dd-radius: 20px;
    --dd-content-radius: 10px;
    --dd-content-first-radius: 10px;
    --dd-content-last-radius: 10px;
    }
    dropdown-element {
    position: relative;
    display: inline-flex;
    align-items: center;
    /* justify-content: center; */
    justify-content: space-between;
    padding: 4px 8px;
    gap: 4px;
    border-radius: var(--dd-radius);
    background: #fafafa;
    box-shadow: 0 2px 3px 0 rgba(0,0,0,0.1);
    cursor: pointer;
    min-width: fit-content;
    max-width: 100%;
    min-height: auto;
    font-family: Arial;
    }
    .dropdown-pre-icon {
    color: #777;
    font-size: 16px;
    font-weight: bold;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    }
    .dropdown-title {
    font-size: 14px;
    font-weight: bold;
    color: #333;
    margin: 0;
    padding: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
    width: 100%;
    text-align: center;
    height: fit-content;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    }
    .dropdown-button {
    /* font-size: 14px; */
    display: inline-flex;
    font-weight: bold;
    color: #333;
    margin: 0;
    padding: 0;
    /* text-transform: uppercase; */
    /* letter-spacing: 1px; */
    }
    dropdown-content {
    display: none;
    flex-direction: column;
    position: absolute;
    top: 110%;
    left: 0;
    right: 0;
    background: #fafafa;
    box-shadow: 3px 3px 16px 0 rgba(0,0,0,0.1);
    border-radius: var(--dd-content-radius);
    gap: 4px;
    width: 100%;
    padding: 0.5em 0;
    z-index: 1;
    }
    dropdown-item {
    font-size: 14px;
    font-weight: normal;
    color: #333;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    white-space: pre;
    padding: 2px 8px;
    text-decoration: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    }
    /*dropdown-item:first-child {
    border-top-left-radius: var(--dd-content-first-radius);
    border-top-right-radius: var(--dd-content-first-radius);
    }
    dropdown-item:last-child {
    border-bottom-left-radius: var(--dd-content-last-radius);
    border-bottom-right-radius: var(--dd-content-last-radius);
    padding-bottom: 1em;
    }*/
    dropdown-element:focus-within dropdown-content {
    display: flex;
    }
    dropdown-item:hover {
    color: #333;
    background: #efefef;
    box-shadow: 0 0 3px 0 #ccc;
    font-weight: 600;
    }
    dropdown-item[active] {
    /* background-color: ; */
    color: #007bff !important;
    }
    :host .dropdown-button {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    }
    .dropdown-search {
    margin: 8px;
    border-radius: 8px;
    padding: 8px;
    outline: none;
    border: none;
    background: #efefef;
    color: #333;
    box-shadow: inset 0 0 3px 0 rgb(0 0 0 / 11%);
    }
    .noselect,
    .no-select {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    }
    .show {
    display: flex;
    }
    `;
            // style.textContent = styleContent;
            // this.appendChild(style);
            const sheet = new CSSStyleSheet();
            sheet.replaceSync(styleContent);
            this.adoptedStyleSheets = [sheet];
        }
    }

    return Dropdown;
})();

customElements.define("dropdown-element", Dropdown);

class DropdownContent extends HTMLElement {}
customElements.define("dropdown-content", DropdownContent);

class DropdownItem extends HTMLElement {}
customElements.define("dropdown-item", DropdownItem);

class DropdownContentGroup extends HTMLElement {}
customElements.define("dropdown-contentgroup", DropdownContentGroup);

// function convertOldDropdowns(target = document) {
//     const oldDropdowns = target.querySelectorAll(".dropdown:not(input)");
//     oldDropdowns.forEach((oldDropdown) => {
//         const newDropdown = document.createElement("dropdown-element");
//         for (const { name, value } of oldDropdown.attributes) {
//             newDropdown.setAttribute(name, value);
//         }
//         newDropdown.setAttribute("title", oldDropdown.getAttribute("title"));
//         for (const { name, value } of oldDropdown.attributes) {
//             console.log(`name: ${name}\nvalue: ${value}\n`)
//             if (name !== "title") {
//                 newDropdown.setAttribute(name, value);
//             }
//         }
//         const preIcon = oldDropdown.querySelector(".dropdown-pre-icon");
//         if (preIcon) {
//             newDropdown.setAttribute("pre-icon", preIcon.textContent);
//         }
//         const newDropdownContent = document.createElement("dropdown-content");

//         const oldItems = oldDropdown.querySelectorAll(".dropdown-item");

//         oldItems.forEach((oldItem) => {
//             const newItem = document.createElement("dropdown-item");
//             newItem.textContent = oldItem.textContent;
//             newItem.setAttribute('value', oldItem.getAttribute('value') || oldItem.textContent);

//             for (const [dataName, dataValue] of Object.entries(oldItem.dataset)) {
//                 newItem.dataset[dataName] = dataValue;
//             }
//             // other attributes
//             for (const { name, value } of oldItem.attributes) {
//                 console.log(`name: ${name}\nvalue: ${value}\n`)
//                 if (name !== "value" && name !== "class") {
//                     newItem.setAttribute(name, value);
//                 }
//             }
//             newDropdownContent.appendChild(newItem);
//         });
//         newDropdown.appendChild(newDropdownContent);
//         oldDropdown.replaceWith(newDropdown);
//     });
// }

const initializeDropdown = (dropdown) => {
    if (!dropdown.__initialized && dropdown instanceof Dropdown) {
        dropdown.__initialized = true;
    }
};

const observer = new MutationObserver(handleMutations);
observer.observe(document.body, { childList: true, subtree: true });

function handleMutations(mutationsList, observer) {
    for (let mutation of mutationsList) {
        if (mutation.type === "childList") {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    // Convert any added old-style dropdowns
                    // if (node.matches('.dropdown:not(input)')) {
                    //     convertOldDropdowns(node);
                    // }
                    // Initialize new dropdowns
                    // if (node.matches('dropdown-element')) {
                    //     initializeDropdown(node);
                    // }
                }
            });
        }
    }
}

function createDropdown(options) {
    const dropdown = document.createElement("dropdown-element");
    // If values are provided, populate the dropdown content
    for (const [name, value] of Object.entries(options.values)) {
        if (key === 'values' && typeof value === 'object') {
            dropdown.setAttribute(key, JSON.stringify(value));
        } else {
            dropdown.setAttribute(key, value);
        }
    }
    return dropdown;
}


document.addEventListener("DOMContentLoaded", function () {
    // convertOldDropdowns();
    const dropdowns = document.querySelectorAll('dropdown-element');
    dropdowns.forEach(initializeDropdown);
});
