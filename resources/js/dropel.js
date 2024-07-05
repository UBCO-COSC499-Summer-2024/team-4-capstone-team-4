const Dropdown = (function () {
    let debugging = true;
    class Dropdown extends HTMLElement {
        constructor() {
            super();
            this._value = null;
            this.toggleDropdown = this.toggleDropdown.bind(this);
            this.handleKeyboardNavigation = this.handleKeyboardNavigation.bind(this);
            this.selectItem = this.selectItem.bind(this);
            this.handleOutsideClick = this.handleOutsideClick.bind(this);
            this.addEventListener("mouseout", this.handleMouseOut.bind(this));
            this.addEventListener("mouseover", this.handleMouseOver.bind(this));
            document.addEventListener("click", this.handleOutsideClick.bind(this));
            this.searchInput = null;
            this.multiple = false;
            this.selectedItems = new Set();
            this.searchable = false;
            this.useExternal = false;
            this.useCustomRegex = false;
            this.regex = 'i';
            this.debugMode = debugging;
            this.searchValue = ""; // Initialize searchValue property
            this.addEventListener("click", this.toggleDropdown);
            this.addEventListener("keydown", this.handleKeyboardNavigation);
            this.addEventListener("dropdown-selected", this.handleDropdownSelected);
            this.addEventListener("change", this.handleDropdownChange.bind(this));
            this.addEventListener("dropdown-item-selected", this.handleDropdownItemSelected.bind(this));
            this.addEventListener("input", this.handleSearchInput.bind(this));
            document.addEventListener('click', this.handleOutsideClick);
        }
        connectedCallback() {
            if (!this.hasAttribute("title")) {
                this.setAttribute("title", "Dropdown");
            }
            this.setAttribute("role", "listbox"); // ARIA role
            this.setAttribute("tabindex", "0"); // Make it focusable
            this.render();
            this.applyDefaultStyles();
            this.setActiveItem();
            this.addDropdownContentListener();
            this.addEventListener("click", this.handleClick.bind(this));
            this.addEventListener("mouseout", this.handleMouseOut.bind(this));
            this.addEventListener("mouseover", this.handleMouseOver.bind(this));
            this.addEventListener("dropdown-selected", this.handleDropdownSelected.bind(this));
            this.addEventListener("change", this.handleDropdownChange.bind(this));
            this.addEventListener("dropdown-item-selected", this.handleDropdownItemSelected.bind(this));
            this.addEventListener("input", this.handleSearchInput.bind(this));
            this.log("addDropdownContentListener executed");
            this.multiple = this.hasAttribute("multiple");
            this.log(this.multiple)
            this.searchable = this.hasAttribute("searchable") ||
                this.hasAttribute("search") ||
                this.hasAttribute("searcheable") ||
                this.hasAttribute("search-box");
            this.log(this.searchable)
            this.useExternal = this.hasAttribute("external") ||
                this.hasAttribute("source") ||
                this.hasAttribute("src");
            this.log(this.useExternal)
            if (this.searchable) {
                this.addSearchFunctionality();
            }
            if (this.multiple) {
                this.addMultiSelectFunctionality();
            }
            if (this.useExternal) {
                this.loadExternalData();
            }
            if (this.hasAttribute('regex')) {
                this.useCustomRegex = true;
                this.regex = this.getAttribute('regex');
            } else {
                this.useCustomRegex = false;
                this.regex = 'i';
            }
            // this.debugMode based on attribute debug, if attribute set with no value then debug mode is true else whatever value is set
            this.debugMode = this.hasAttribute("debug") ? true : this.getAttribute("debug") || false;
            this.log("Debug mode:", this.debugMode);
            this.toggleDebugging(this.debugMode);
            const dropdownContent = this.querySelector("dropdown-content");
            // if no dropdownContent, create one
            if (!dropdownContent) {
                const dropdownContent = document.createElement("dropdown-content");
                this.appendChild(dropdownContent);
            }
            if (dropdownContent && !dropdownContent.hasChildNodes()) {
                const values = JSON.parse(this.getAttribute("values") || "{}");
                for (const [name, value] of Object.entries(values)) {
                    this.appendValue(name, value);
                }
            }
        }
        handleDropdownChange(event) {
            this.log("Dropdown value changed:", event.target.value);
        }
        handleDropdownItemSelected(event) {
            this.log("Dropdown item selected:", event.detail.value);
        }
        disconnectedCallback() {
            this.removeEventListener("click", this.handleClick.bind(this));
            this.removeEventListener("mouseout", this.handleMouseOut.bind(this));
            this.removeEventListener("mouseover", this.handleMouseOver.bind(this));
            this.removeEventListener("dropdown-selected", this.handleDropdownSelected.bind(this));
            this.removeEventListener("click", this.toggleDropdown);
            this.removeEventListener("keydown", this.handleKeyboardNavigation);
            this.removeEventListener("dropdown-selected", this.handleDropdownSelected);
            document.removeEventListener('click', this.handleOutsideClick);
        }
        toggleDebugging(value) {
            // console.log("toggleDebugging invoked with value:", value);
            if (value === undefined) {
                debugging = !debugging;
            } else {
                debugging = value;
            }
            // console.log("Debugging set to:", debugging);
            return debugging;
        }
        log(...args) {
            if (this.debugMode) {
                console.log(...args);
            }
        }
        async loadExternalData() {
            let source = this.getAttribute("external") ||
                this.getAttribute("source") ||
                this.getAttribute("src");
            if (!source) return;
            // if source type of url or path, fetch
            // else if source type of object, use object
            // else if source type of function, use function
            // Try to parse JSON
            try {
                const parsedData = JSON.parse(source);
                if (typeof parsedData === 'object') {
                    this.processData(parsedData);
                    return;
                }
            } catch (error) {
                if (typeof source === 'function') {
                    const data = source();
                    if (!data || typeof data !== 'object') {
                        throw new Error("Dropdown data must be an object");
                    }
                    await this.processData(data);
                    return;
                }
                // Check if source is a URL or path
                if (this.isValidURL(source) || source.startsWith("/")) {
                    this.fetchData(source);
                    return;
                }
                console.warn("Invalid data source provided:", source);
            }
        }
        isValidSourceUrl(url) {
            try {
                new URL(url);
                return true;
            } catch (error) {
                return false;
            }
        }
        fetchData(url) {
            // if it doesn't start with http or https, assume it's a relative path
            if (!url.startsWith("http") && !url.startsWith("https")) {
                url = `/${url}`;
            }
            fetch(url)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        return response.json();
                    } else {
                        return response.text();
                    }
                })
                .then(this.processData.bind(this))
                .catch((error) => {
                    const errorEvent = new CustomEvent('dropdown-source-error', { detail: error });
                    this.dispatchEvent(errorEvent);
                    console.warn(`Error loading external data from ${url}`, error);
                });
        }
        async processData(data) {
            const dataLoadedEvent = new CustomEvent('dropdown-source-loaded', { detail: data });
            this.dispatchEvent(dataLoadedEvent);
            this.log("Data:", data);
            // Assuming the data is an object for JSON content type
            if (typeof data === 'object' && data !== null) {
                for (const [name, value] of Object.entries(data)) {
                    await this.appendValue(name, value);
                }
            } else {
                // Handle other content types like XML or plain text here
            }
        }
        formDisabledCallback(disabled) {
            // Handle form disabled state
        }
        formReset() {
            // Reset the element's state when the form is reset
        }
        addSearchFunctionality() {
            const searchInput = document.createElement("input");
            searchInput.setAttribute("type", "text");
            searchInput.setAttribute("placeholder", "Search...");
            searchInput.setAttribute("autocomplete", "off");
            searchInput.setAttribute("autocorrect", "off");
            searchInput.setAttribute("autocapitalize", "off");
            searchInput.setAttribute("spellcheck", "false");
            searchInput.setAttribute("tabindex", "-1");
            searchInput.className = "dropdown-search";
            // put the searchInput in content
            const dropdownContent = this.querySelector("dropdown-content");
            dropdownContent.insertBefore(searchInput, dropdownContent.firstChild);
            this.searchInput = searchInput;
            searchInput.addEventListener("input", this.handleSearchInput.bind(this));
            searchInput.addEventListener("keydown", this.handleSearchInput.bind(this));
            searchInput.addEventListener("keyup", this.handleSearchInput.bind(this));
        }
        handleSearchInput(e) {
            this.log("handleSearchInput invoked with event:", e);
            const searchValue = this.searchInput.value.trim();
            this.log("Search value:", searchValue);
            const dropdownItems = this.querySelectorAll("dropdown-item");
            this.log("Dropdown items:", dropdownItems);
            this.searchValue = searchValue;
            if (searchValue.length > 0) {
                if (this.useCustomRegex) {
                    const regex = new RegExp(searchValue, this.regex);
                    dropdownItems.forEach((item) => {
                        const itemText = item.textContent;
                        if (regex.test(itemText)) {
                            item.style.display = "block";
                        } else {
                            item.style.display = "none";
                        }
                    });
                } else {
                    const escapedSearchValue = searchValue.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                    const regexPattern = escapedSearchValue.split(/\s+/).join('.*');
                    const regex = new RegExp(regexPattern, 'i');
                    dropdownItems.forEach((item) => {
                        const itemText = item.textContent;
                        if (itemText.match(regex)) {
                            item.style.display = "block";
                        } else {
                            item.style.display = "none";
                        }
                    });
                }
                const searchPerformedEvent = new CustomEvent("dropdown-searched", {
                    detail: { searchValue }
                });
                this.dispatchEvent(searchPerformedEvent);
            } else {
                dropdownItems.forEach((item) => {
                    item.style.display = "block";
                });
            }
            this.updateBorderRadius();
            if (this.multiple) {
                this.updateDropdownTitle();
            }
        }
        updateBorderRadius() {
            const visibleItems = Array.from(this.querySelectorAll("dropdown-item:not([style*='display: none'])"));
            // const firstVisibleItem = visibleItems[0];
            // const lastVisibleItem = visibleItems[visibleItems.length - 1];
            // if (lastVisibleItem) {
            //     lastVisibleItem.style.borderBottomLeftRadius = "var(--dd-content-last-radius)";
            //     lastVisibleItem.style.borderBottomRightRadius = "var(--dd-content-last-radius)";
            // }
        }
        setSelected(value) {
            if (value) {
                const dropdownItem = this.querySelector(`dropdown-item[value="${value}"]`);
                if (dropdownItem) {
                    dropdownItem.setAttribute("active", "");
                }
            } else {
                const selectedItems = this.querySelectorAll("dropdown-item[active]");
                selectedItems.forEach((item) => {
                    item.removeAttribute("active");
                });
                this.selectedItems.clear();
                this.selectItem(null);
            }
            this.updateDropdownTitle();
        }
        getSelected() {
            // if multiple return array of selected items
            // else return selected item
            if (this.multiple) {
                return this.selectedItems;
            } else {
                return this.selectedValue;
            }
        }
        clearSelected() {
            // clear all selected items
            const selectedItems = this.querySelectorAll("dropdown-item[active]");
            if (selectedItems.length === 0) return;
            selectedItems.forEach((item) => {
                item.removeAttribute("active");
            });
            this.selectedItems.clear();
            this.selectItem(null);
            this.updateDropdownTitle();
        }
        updateDropdownTitle() {
            const selectedItems = this.querySelectorAll("dropdown-item[active]");
            const selectedItemsCount = selectedItems.length;
            const dropdownTitle = this.querySelector(".dropdown-title");
            if (selectedItemsCount === 0) {
                dropdownTitle.textContent = this.getAttribute("title");
            } else if (selectedItemsCount === 1) {
                dropdownTitle.textContent = selectedItems[0].textContent;
            } else {
                dropdownTitle.textContent = `${selectedItemsCount} item${selectedItemsCount > 1 ? "s" : ""} selected`;
            }
        }
        addMultiSelectFunctionality() {
            this.addEventListener("keydown", this.handleMultiSelect.bind(this));
        }
        handleMultiSelect(e) {
            this.log("handleMultiSelect invoked with event:", e);
            const target = e.target;
            if (target.tagName.toLowerCase() === "dropdown-item") {
                let selectedValue;
                if (target.hasAttribute("active")) {
                    target.removeAttribute("active");
                    selectedValue = null;
                } else {
                    target.setAttribute("active", "");
                    selectedValue = target.getAttribute("value") || target.textContent;
                }
                // Trigger custom event for item selection
                const itemSelectedEvent = new CustomEvent("dropdown-item-selected", {
                    detail: { value: selectedValue, selected: target.hasAttribute("active") }
                });
                this.dispatchEvent(itemSelectedEvent);
            }
            this.updateSelectedItems();
            this.updateDropdownTitle();
        }
        updateSelectedItems() {
            const selectedItems = this.querySelectorAll("dropdown-item[active]");
            this.selectedItems.clear();
            selectedItems.forEach((item) => {
                this.selectedItems.add(item.getAttribute("value"));
                this.log("Added item to selectedItems:", item.getAttribute("value"))
            });
        }
        handleDropdownSelected(e) {
            this.log("handleDropdownSelected invoked with event:", e);
            const selectedValue = event.detail.value;
            this.log("Selected value:", selectedValue);
        }
        get value() {
            return this._value;
        }
        set value(val) {
            const oldValue = this._value;
            this._value = val;
            if (oldValue !== this._value) {
                this.dispatchEvent(new Event('change'));
            }
        }
        async render() {
            this.className = "dropdown-element";
            // Pre-icon
            if (this.hasAttribute("data-pre-icon") || this.hasAttribute("pre-icon") || this.hasAttribute("preIcon") || this.hasAttribute("data-preIcon")) {
                if (!this.querySelector('.dropdown-pre-icon')) {
                    const dropdownPreIcon = document.createElement("span");
                    dropdownPreIcon.className = "material-symbols-outlined dropdown-pre-icon icon noselect";
                    const preIcon = this.getAttribute("data-pre-icon") ?? this.getAttribute("pre-icon") ?? this.getAttribute("data-preIcon") ?? this.getAttribute("preIcon")
                    dropdownPreIcon.textContent = preIcon ?? "list";
                    this.insertBefore(dropdownPreIcon, this.firstChild);
                } else {
                    if (!this.querySelector('.dropdown-pre-icon').textContent) {
                        this.querySelector(".dropdown-pre-icon").textContent = this.getAttribute("data-pre-icon") ?? this.getAttribute("pre-icon") ?? this.getAttribute("data-preIcon") ?? this.getAttribute("preIcon") ?? "list";
                    }
                }
            }
            // Title
            if (!this.querySelector('.dropdown-title')) {
                const dropdownTitle = document.createElement("span");
                dropdownTitle.className = "dropdown-title noselect";
                dropdownTitle.textContent = this.getAttribute("title");
                this.insertBefore(dropdownTitle, this.querySelector('.dropdown-pre-icon')?.nextSibling);
            } else {
                this.querySelector(".dropdown-title").innerHTML = this.getAttribute("title") ?? "";
            }
            // Button
            if (!this.querySelector(".dropdown-button")) {
                const dropdownButton = document.createElement("i");
                dropdownButton.className = "material-symbols-outlined dropdown-button noselect";
                dropdownButton.textContent = "arrow_drop_down";
                this.insertBefore(dropdownButton, this.querySelector('.dropdown-title')?.nextSibling);
            } else {
                this.querySelector(".dropdown-button").textContent = "arrow_drop_down";
            }
            // Search
            if (this.searchable) {
                this.addSearchFunctionality();
            }
            let dropdownContent = this.querySelector("dropdown-content");
            if (!dropdownContent) {
                dropdownContent = document.createElement("dropdown-content");
                this.appendChild(dropdownContent);
            } else {
                this.appendChild(dropdownContent);
            }
            if (this.searchable) {
                this.insertBefore(this.searchInput, this.firstChild);
            }
        }
        async appendValue(name, value) {
            let dropdownContent = this.querySelector("dropdown-content");
            if (!dropdownContent) {
                dropdownContent = document.createElement("dropdown-content");
                this.appendChild(dropdownContent);
            }
            const dropdownItem = document.createElement("dropdown-item");
            dropdownItem.className = "dropdown-item";
            dropdownItem.textContent = name;
            dropdownItem.setAttribute("value", name); // Add value attribute
            if (value?.hasOwnProperty("data")) {
                for (const [dataName, dataValue] of Object.entries(value.data)) {
                    dropdownItem.dataset[dataName] = dataValue;
                }
            }
            dropdownContent?.appendChild(dropdownItem);
            return new Promise(resolve => setTimeout(() => resolve(), 0));  // Resolve after next event loop
        }
        toggleDropdown(e) {
            this.log("toggleDropdown invoked with event:", e, "and this:", this);
            const dropdownContent = this.querySelector("dropdown-content"); // Changed from .dropdown-content to dropdown-content
            if (dropdownContent) {
                if (dropdownContent.classList.contains("show")) {
                    dropdownContent.classList.remove("show");
                    this.querySelector(".dropdown-button").textContent = "arrow_drop_down";
                } else {
                    dropdownContent.classList.add("show");
                    this.querySelector(".dropdown-button").textContent = "arrow_drop_up";
                }
                e.stopPropagation();  // Prevent the event from reaching the document click handler
            }
        }
        handleOutsideClick(e) {
            // If the click is outside the dropdown, close the dropdown content
            if (!this.contains(e.target)) {
                const dropdownContent = this.querySelector("dropdown-content");
                if (dropdownContent?.classList.contains("show")) {
                    dropdownContent.classList.remove("show");
                    let dropdownButton = this.querySelector(".dropdown-button");
                    if (dropdownButton) {
                        dropdownButton.textContent = "arrow_drop_down";
                        this.log("Dropdown button text changed to:", dropdownButton.textContent);
                    }
                }
            }
        }
        setActiveItem() {
            const settingValue = this.getAttribute("value");
            const activeItem = this.querySelector(`[value="${settingValue}"]`);
            if (this.activeItem) {
                activeItem.classList.add("active");
                activeItem.setAttribute("active", "");
                this.querySelect(".dropdown-title").textContent = activeItem.textContent;
                this.value = activeItem.dataset.value; // Set the value attribute
                this.value = activeItem.getAttribute("value");
            }
        }
        handleKeyboardNavigation(e) {
            const items = Array.from(this.querySelectorAll("dropdown-item"));
            const activeItem = this.querySelector("dropdown-item[active]");
            const currentIndex = items.indexOf(activeItem);
            switch (e.key) {
                case "ArrowUp":
                    const prevItem = items[currentIndex - 1] || items[items.length - 1];
                    if (prevItem) prevItem.focus();
                    break;
                case "ArrowDown":
                    const nextItem = items[currentIndex + 1] || items[0];
                    if (nextItem) nextItem.focus();
                    break;
                case "Enter":
                    if (document.activeElement.classList.contains("dropdown-item")) {
                        this.selectItem(document.activeElement);
                    }
                    break;
            }
        }
        getItems() {
            return Array.from(this.querySelectorAll("dropdown-item"));
        }
        selectItem(item) {
            this.log("selectItem invoked with item:", item);
            const previouslyActiveItem = this.querySelector("dropdown-item[active], dropdown-item[active=''], dropdown-item[active=true]");
            this.log("Previously active item:", previouslyActiveItem);
            if (previouslyActiveItem) {
                previouslyActiveItem.removeAttribute("active");
                this.querySelector(".dropdown-title").textContent = this.getAttribute("title");
                this.value = null;
            }
            // If the item is a string, retrieve the corresponding dropdown item
            if (typeof item === "string") {
                let itemValue = item;
                item = this.querySelector(`dropdown-item[value="${itemValue}"], dropdown-item[data-value="${itemValue}"]`);
                if (!item) {
                    this.log("Item not found by value:", itemValue);
                    let items = this.querySelectorAll("dropdown-item");
                    this.log(items);
                    items.forEach((dropdownItem) => {
                        if (dropdownItem.textContent === itemValue) {
                            item = dropdownItem;
                        }
                    });
                    if (item) {
                        this.log("Item found by textContent:", item);
                    } else {
                        this.log("Item not found by textContent:", itemValue);
                    }
                } else {
                    this.log("Item found by value:", item);
                }
            }
            if (!item) {
                // Clear Selected
                this.clearSelected();
            } else {
                this.log(item);
                // Select the clicked item
                item?.setAttribute("active", "");
                this.log("Active attribute set on item:", item);
                this.value = item.getAttribute("value");
                this.log("Value set as:", this.value);
                const groupName = item.closest("dropdown-contentgroup")?.getAttribute("category");
                if (groupName) {
                    this.dataset.group = groupName;
                    this.log("Group name set as:", groupName);
                } else {
                    delete this.dataset.group;
                    this.log("Group name deleted");
                }
                const titleElement = this.querySelector(".dropdown-title");
                this.log("Title element:", titleElement);
                if (titleElement) {
                    this.log("Before:", titleElement.textContent);
                    titleElement.textContent = item.textContent;
                    this.log("After:", titleElement.textContent);
                }
            }
            // close the dropdown-content if not multiple and is open
            if (!this.multiple && this.querySelector("dropdown-content.show")) {
                this.closeDropdown();
            }
            this.dispatchEvent(new Event("change"));
            const selectedValue = item?.getAttribute("value");
            const dropdownSelectedEvent = new CustomEvent("dropdown-item-selected", {
                detail: { value: selectedValue },
                // bubbles: true
            });
            this.dispatchEvent(dropdownSelectedEvent);
            return true;
        }
        addDropdownContentListener() {
            this.querySelector("dropdown-content").addEventListener("click", (e) => {
                this.log("Dropdown content clicked. Target:", e.target);
                if (e.target.tagName.toLowerCase() === "dropdown-item") {
                    this.log("Dropdown item clicked:", e.target);
                    // if not multiple, this.selectItem, else
                    if (this.multiple) {
                        this.handleMultiSelect(e);
                    } else {
                        this.selectItem(e.target);
                        this.toggleDropdown(e);
                    }
                } else {
                    this.log("Clicked inside dropdown content but not on a dropdown item");
                }
            });
        }
        handleMouseOut(e) {
            // this.log("Mouse out event triggered");
            if (!this.contains(e.relatedTarget)) {
                this.closeDropdown();
            }
        }
        handleMouseOver(e) {
            // this.log("Mouse over event triggered");
            if (!this.contains(e.relatedTarget)) {
                this.openDropdown();
            }
        }
        handleClick(e) {
            this.log("Click event triggered");
            if (this.contains(e.target)) {
                if (this.querySelector("dropdown-content.show")) {
                    this.closeDropdown();
                } else {
                    this.openDropdown();
                }
            }
        }
        openDropdown() {
            this.log("openDropdown invoked");
            const dropdownContent = this.querySelector("dropdown-content");
            if (dropdownContent) {
                dropdownContent.classList.add("show");
                this.querySelector(".dropdown-button").textContent = "arrow_drop_up";
            }
        }
        closeDropdown() {
            this.log("closeDropdown invoked");
            const dropdownContent = this.querySelector("dropdown-content");
            if (dropdownContent) {
                dropdownContent.classList.remove("show");
                this.querySelector(".dropdown-button").textContent = "arrow_drop_down";
            }
        }
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
            style.textContent = styleContent;
            this.appendChild(style);
            const sheet = new CSSStyleSheet();
            sheet.replaceSync(styleContent);
            this.adoptedStyleSheets = [sheet];
        }
    }
    return Dropdown;
})();
customElements.define("dropdown-element", Dropdown);
class DropdownContent extends HTMLElement { }
customElements.define("dropdown-content", DropdownContent);
class DropdownItem extends HTMLElement { }
customElements.define("dropdown-item", DropdownItem);
class DropdownContentGroup extends HTMLElement { }
customElements.define("dropdown-contentgroup", DropdownContentGroup);
function convertOldDropdowns(target = document) {
    const oldDropdowns = target.querySelectorAll(".dropdown:not(input)");
    oldDropdowns.forEach((oldDropdown) => {
        const newDropdown = document.createElement("dropdown-element");
        // newDropdown.setAttribute("is", "dropdown-element");
        newDropdown.setAttribute("id", oldDropdown.id);
        newDropdown.setAttribute("title", oldDropdown.getAttribute("title"));
        // add all the other attributes that exist on the dropdown
        for (const { name, value } of oldDropdown.attributes) {
            console.log(`name: ${name}\nvalue: ${value}\n`)
            if (name !== "title") {
                newDropdown.setAttribute(name, value);
            }
        }
        const preIcon = oldDropdown.querySelector(".dropdown-pre-icon");
        if (preIcon) {
            newDropdown.setAttribute("data-pre-icon", preIcon.textContent);
        }
        const newDropdownContent = document.createElement("dropdown-content");
        const oldItems = oldDropdown.querySelectorAll(".dropdown-item");
        oldItems.forEach((oldItem) => {
            const newItem = document.createElement("dropdown-item");
            newItem.textContent = oldItem.textContent;
            newItem.setAttribute("value", oldItem.textContent); // Set value attribute
            // If there's extra data for the item, add it as dataset attributes
            if (oldItem.dataset) {
                for (const [dataName, dataValue] of Object.entries(oldItem.dataset)) {
                    newItem.dataset[dataName] = dataValue;
                }
            }
            // other attributes
            for (const { name, value } of oldItem.attributes) {
                console.log(`name: ${name}\nvalue: ${value}\n`)
                if (name !== "value" && name !== "class") {
                    newItem.setAttribute(name, value);
                }
            }
            newDropdownContent.appendChild(newItem);
        });
        newDropdown.appendChild(newDropdownContent);
        oldDropdown.replaceWith(newDropdown);
        // Debugging: Print the dropdown title before and after updating
        // console.log("Before title update:", newDropdown.querySelector(".dropdown-title").textContent);
        // console.log(newDropdown)
        // console.log(oldDropdown)
        // const oldActiveItems = oldDropdown.querySelectorAll(".dropdown-item.active");
        // // Update the dropdown title if there's an active item
        // if (oldActiveItems.length > 0) {
        //     if (newDropdown.hasAttribute("multiple")) {
        //         const dropdownTitle = newDropdown.querySelector(".dropdown-title");
        //         dropdownTitle.textContent = `${oldActiveItems.length} item${oldActiveItems.length > 1 ? "s" : ""} selected`;
        //     } else {
        //         const dropdownTitle = newDropdown.querySelector(".dropdown-title");
        //         dropdownTitle.textContent = oldActiveItems[0].textContent;
        //     }
        // } else {
        //     const dropdownTitle = newDropdown.querySelector(".dropdown-title");
        //     dropdownTitle.textContent = newDropdown.getAttribute("title");
        // }
        // // Debugging: Print the dropdown title after updating
        // console.log("After title update:", newDropdown.querySelector(".dropdown-title").textContent);
        // set properties for the newDropdown
        // newDropdown.multiple = newDropdown.hasAttribute("multiple");
        // newDropdown.searchable = newDropdown.hasAttribute("searchable") ||
        // newDropdown.hasAttribute("search") ||
        // newDropdown.hasAttribute("searcheable") ||
        // newDropdown.hasAttribute("search-box");
        // newDropdown.useExternal = newDropdown.hasAttribute("external") ||
        // newDropdown.hasAttribute("source") ||
        // newDropdown.hasAttribute("src");
        // if (newDropdown.hasAttribute('regex')) {
        //     newDropdown.useCustomRegex = true;
        //     newDropdown.regex = newDropdown.getAttribute('regex');
        // }
        // // add event listeners
        // newDropdown.addEventListener("click", newDropdown.toggleDropdown);
        // newDropdown.addEventListener("keydown", newDropdown.handleKeyboardNavigation);
        // newDropdown.addEventListener("dropdown-selected", newDropdown.handleDropdownSelected);
        // document.addEventListener('click', newDropdown.handleOutsideClick);
        // // selected item(s)
        // newDropdown.selectedItems = new Set();
        // newDropdown.updateSelectedItems();
    });
}
const initializeDropdown = (dropdown) => {
    if (!dropdown.__initialized) {
        dropdown.__initialized = true;
    }
};
function handleMutations(mutationsList, observer) {
    for (let mutation of mutationsList) {
        if (mutation.type === "childList") {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    // Convert old-style dropdowns if any
                    if (node.classList.contains("dropdown-element")) {
                        convertOldDropdowns(node);
                    }
                    // Also, check for any old-style dropdowns among the descendants of the added node
                    const oldDropdowns = node.querySelectorAll(".dropdown:not(input)");
                    oldDropdowns.forEach(convertOldDropdowns);
                    // Initialize new-style dropdowns
                    if (node.matches('dropdown-element')) {
                        initializeDropdown(node);
                    }
                    // Also, check for any new-style dropdowns among the descendants of the added node
                    const dropdowns = node.querySelectorAll('dropdown-element');
                    dropdowns.forEach(initializeDropdown);
                }
            });
        }
    }
}
const observer = new MutationObserver(handleMutations);
observer.observe(document.body, { childList: true, subtree: true });
document.addEventListener("DOMContentLoaded", function () {
    convertOldDropdowns(); // Convert old dropdowns after document is loaded
    const dropdowns = document.querySelectorAll('dropdown-element');
    dropdowns.forEach(initializeDropdown);
});
function createDropdown(options) {
    // Create the main dropdown element
    const dropdown = document.createElement("dropdown-element");
    dropdown.setAttribute("title", options.title);
    if (options.preIcon) {
        dropdown.setAttribute("data-pre-icon", options.preIcon);
    }
    // If values are provided, populate the dropdown content
    if (options.values) {
        const dropdownContent = document.createElement("dropdown-content");
        for (const [name, value] of Object.entries(options.values)) {
            const dropdownItem = document.createElement("dropdown-item");
            dropdownItem.textContent = name;
            dropdownItem.setAttribute("value", name); // Set value attribute
            // If there's extra data for the item, add it as dataset attributes
            if (value.data) {
                for (const [dataName, dataValue] of Object.entries(value.data)) {
                    dropdownItem.dataset[dataName] = dataValue;
                }
            }
            dropdownContent.appendChild(dropdownItem);
        }
        dropdown.appendChild(dropdownContent);
    }
    return dropdown;
}
