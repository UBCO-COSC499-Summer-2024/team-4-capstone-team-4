// const DropdownInput = (function() {
//     let debugging = true;
//     class DropdownInput extends HTMLInputElement {
//         constructor() {
//             super();
//             this._value = null;
//             this.toggleDropdown = this.toggleDropdown.bind(this);
//             this.handleKeyboardNavigation = this.handleKeyboardNavigation.bind(this);
//             this.selectItem = this.selectItem.bind(this);
//             this.handleOutsideClick = this.handleOutsideClick.bind(this);
//             this.addEventListener("mouseout", this.handleMouseOut.bind(this));
//             this.addEventListener("mouseover", this.handleMouseOver.bind(this));
//             document.addEventListener("click", this.handleOutsideClick.bind(this));
//             this.searchInput = null;
//             this.multiple = false;
//             this.selectedItems = new Set();
//             this.searchable = false;
//             this.useExternal = false;
//             this.useCustomRegex = false;
//             this.regex = 'i';
//             this.searchValue = ""; // Initialize searchValue property
//             this.addEventListener("click", this.toggleDropdown);
//             this.addEventListener("keydown", this.handleKeyboardNavigation);
//             this.addEventListener("dropdown-selected", this.handleDropdownSelected);
//             document.addEventListener('click', this.handleOutsideClick);
//         }

//         connectedCallback() {
//             if (!this.hasAttribute("title")) {
//                 this.setAttribute("title", "Dropdown");
//             }
//             this.setAttribute("role", "listbox"); // ARIA role
//             this.setAttribute("tabindex", "0"); // Make it focusable
//             this.render();
//             this.applyDefaultStyles();
//             this.setActiveItem();
//             this.addDropdownContentListener();
//             this.addEventListener("click", this.handleClick.bind(this));
//             this.addEventListener("mouseout", this.handleMouseOut.bind(this));
//             this.addEventListener("mouseover", this.handleMouseOver.bind(this));
//             this.addEventListener("dropdown-selected", this.handleDropdownSelected.bind(this));
//             this.log("addDropdownContentListener executed");
//             this.multiple = this.hasAttribute("multiple");
//             console.log(this.multiple)
//             this.searchable = this.hasAttribute("searchable") ||
//             this.hasAttribute("search") ||
//             this.hasAttribute("searcheable") ||
//             this.hasAttribute("search-box");
//             console.log(this.searchable)
//             this.useExternal = this.hasAttribute("external") ||
//             this.hasAttribute("source") ||
//             this.hasAttribute("src");
//             console.log(this.useExternal)
//             if (this.searchable) {
//                 this.addSearchFunctionality();
//             }
//             if (this.multiple) {
//                 this.addMultiSelectFunctionality();
//             }
//             if (this.useExternal) {
//                 this.loadExternalData();
//             }
//             if (this.hasAttribute('regex')) {
//                 this.useCustomRegex = true;
//                 this.regex = this.getAttribute('regex');
//             } else {
//                 this.useCustomRegex = false;
//                 this.regex = 'i';
//             }

//             const dropdownContent = this.querySelector("dropdown-content");
//             if (dropdownContent && !dropdownContent.hasChildNodes()) {
//                 const values = JSON.parse(this.getAttribute("values") || "{}");
//                 for (const [name, value] of Object.entries(values)) {
//                     this.appendValue(name, value);
//                 }
//             }
//         }

//         toggleDebugging() {
//             debugging = !debugging;
//             return debugging;
//         }

//         log(...args) {
//             if (debugging) {
//                 console.log(...args);
//             }
//         }

//         loadExternalData() {
//             const source = this.getAttribute("external") ||
//             this.getAttribute("source") ||
//             this.getAttribute("src");
//             if (!source) return;
//             if (source) {
//                 fetch(source)
//                 .then((response) => {
//                     if (!response.ok) {
//                         throw new Error(`HTTP error! status: ${response.status}`);
//                     }
//                     // if response type xml, response .text()
//                     // if response type json, response .json()
//                     const contentType = response.headers.get("content-type");
//                     if (contentType && contentType.indexOf("application/json") !== -1) {
//                         return response.json();
//                     } else {
//                         return response.text();
//                     }
//                 })
//                 .then((data) => {
//                     const dataLoadedEvent = new CustomEvent('dropdown-source-loaded', { detail: data });
//                     this.dispatchEvent(dataLoadedEvent);
//                     this.log("Data:", data);
//                     for (const [name, value] of Object.entries(data)) {
//                         this.appendValue(name, value);
//                     }
//                 })
//                 .catch((error) => {
//                     const errorEvent = new CustomEvent('dropdown-source-error', { detail: error });
//                     this.dispatchEvent(errorEvent);
//                     console.warn(`Error loading external data from ${source}`, error);
//                 });
//             }
//         }

//         formDisabledCallback(disabled) {
//             // Handle form disabled state

//         }

//         formReset() {
//             // Reset the element's state when the form is reset
//         }

//         addSearchFunctionality() {
//             const searchInput = document.createElement("input");
//             searchInput.setAttribute("type", "text");
//             searchInput.setAttribute("placeholder", "Search...");
//             searchInput.setAttribute("autocomplete", "off");
//             searchInput.setAttribute("autocorrect", "off");
//             searchInput.setAttribute("autocapitalize", "off");
//             searchInput.setAttribute("spellcheck", "false");
//             searchInput.setAttribute("tabindex", "-1");
//             searchInput.className = "dropdown-search";
//             // put the searchInput in content
//             const dropdownContent = this.querySelector("dropdown-content");
//             dropdownContent.insertBefore(searchInput, dropdownContent.firstChild);

//             this.searchInput = searchInput;
//             searchInput.addEventListener("input", this.handleSearchInput.bind(this));
//             searchInput.addEventListener("keydown", this.handleSearchInput.bind(this));
//             searchInput.addEventListener("keyup", this.handleSearchInput.bind(this));
//         }

//         handleSearchInput(e) {
//             this.log("handleSearchInput invoked with event:", e);
//             const searchValue = this.searchInput.value.trim();
//             this.log("Search value:", searchValue);
//             const dropdownItems = this.querySelectorAll("dropdown-item");
//             this.log("Dropdown items:", dropdownItems);
//             this.searchValue = searchValue;

//             if (searchValue.length > 0) {
//                 if (this.useCustomRegex) {
//                     const regex = new RegExp(searchValue, this.regex);
//                     dropdownItems.forEach((item) => {
//                         const itemText = item.textContent;
//                         if (regex.test(itemText)) {
//                             item.style.display = "block";
//                         } else {
//                             item.style.display = "none";
//                         }
//                     });
//                 } else {
//                     const escapedSearchValue = searchValue.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
//                     const regexPattern = escapedSearchValue.split(/\s+/).join('.*');
//                     const regex = new RegExp(regexPattern, 'i');
//                     dropdownItems.forEach((item) => {
//                         const itemText = item.textContent;
//                         if (itemText.match(regex)) {
//                             item.style.display = "block";
//                         } else {
//                             item.style.display = "none";
//                         }
//                     });
//                 }
//                 const searchPerformedEvent = new CustomEvent("dropdown-searched", {
//                     detail: { searchValue }
//                 });
//                 this.dispatchEvent(searchPerformedEvent);
//             } else {
//                 dropdownItems.forEach((item) => {
//                     item.style.display = "block";
//                 });
//             }

//             this.updateBorderRadius();

//             if (this.multiple) {
//                 this.updateDropdownTitle();
//             }
//         }

//         updateBorderRadius() {
//             const visibleItems = Array.from(this.querySelectorAll("dropdown-item:not([style*='display: none'])"));
//             // const firstVisibleItem = visibleItems[0];
//             const lastVisibleItem = visibleItems[visibleItems.length - 1];

//             if (lastVisibleItem) {
//                 lastVisibleItem.style.borderBottomLeftRadius = "var(--dd-content-last-radius)";
//                 lastVisibleItem.style.borderBottomRightRadius = "var(--dd-content-last-radius)";
//             }
//         }

//         updateDropdownTitle() {
//             const selectedItems = this.querySelectorAll("dropdown-item[active]");
//             const selectedItemsCount = selectedItems.length;
//             const dropdownTitle = this.querySelector(".dropdown-title");
//             if (selectedItemsCount === 0) {
//                 dropdownTitle.textContent = this.getAttribute("title");
//             } else if (selectedItemsCount === 1) {
//                 dropdownTitle.textContent = selectedItems[0].textContent;
//             } else {
//                 dropdownTitle.textContent = `${selectedItemsCount} item${selectedItemsCount > 1 ? "s" : ""} selected`;
//             }
//         }

//         addMultiSelectFunctionality() {
//             this.addEventListener("keydown", this.handleMultiSelect.bind(this));
//         }

//         handleMultiSelect(e) {
//             this.log("handleMultiSelect invoked with event:", e);
//             const target = e.target;
//             if (target.tagName.toLowerCase() === "dropdown-item") {
//                 let selectedValue;
//                 if(target.hasAttribute("active")) {
//                     target.removeAttribute("active");
//                     selectedValue = null;
//                 } else {
//                     target.setAttribute("active", "");
//                     selectedValue = target.getAttribute("value") || target.textContent;
//                 }
//                 // Trigger custom event for item selection
//                 const itemSelectedEvent = new CustomEvent("dropdown-item-selected", {
//                     detail: { value: selectedValue, selected: target.hasAttribute("active") }
//                 });
//                 this.dispatchEvent(itemSelectedEvent);
//             }

//             this.updateSelectedItems();
//             this.updateDropdownTitle();
//         }

//         updateSelectedItems() {
//             const selectedItems = this.querySelectorAll("dropdown-item[active]");
//             this.selectedItems.clear();
//             selectedItems.forEach((item) => {
//                 this.selectedItems.add(item.getAttribute("value"));
//                 this.log("Added item to selectedItems:", item.getAttribute("value"))
//             });
//         }

//         disconnectedCallback() {
//             this.removeEventListener("click", this.handleClick.bind(this));
//             this.removeEventListener("mouseout", this.handleMouseOut.bind(this));
//             this.removeEventListener("mouseover", this.handleMouseOver.bind(this));
//             this.removeEventListener("dropdown-selected", this.handleDropdownSelected.bind(this));
//         }

//         handleDropdownSelected(e) {
//             this.log("handleDropdownSelected invoked with event:", e);
//             const selectedValue = event.detail.value;
//             this.log("Selected value:", selectedValue);
//         }

//         get value() {
//             // return this._value;
//             return super.value;
//         }

//         set value(val) {
//             super.value = val;
//             const oldValue = this._value;
//             this._value = val;
//             if (oldValue !== this._value) {
//                 this.dispatchEvent(new Event('change'));
//             }
//         }

//         get title() {
//             return this.getAttribute("title");
//         }

//         set title(val) {
//             this.setAttribute("title", val);
//         }

//         focus() {
//             super.focus();
//             this.querySelector(".dropdown-button").focus();
//         }

//         blur() {
//             super.blur();
//             this.querySelector(".dropdown-button").blur();
//         }

//         render() {
//             this.className = "dropdown-input";

//             // Pre-icon
//             if (this.hasAttribute("data-pre-icon")) {
//                 const dropdownPreIcon = document.createElement("i");
//                 dropdownPreIcon.className = "material-symbols-outlined dropdown-pre-icon noselect";
//                 if (this.getAttribute("data-pre-icon")) {
//                     dropdownPreIcon.textContent = this.getAttribute("data-pre-icon");
//                 } else {
//                     dropdownPreIcon.textContent = "list";
//                 }
//                 this.appendChild(dropdownPreIcon);
//             }

//             // Title
//             const dropdownTitle = document.createElement("span");
//             dropdownTitle.className = "dropdown-title noselect";
//             dropdownTitle.textContent = this.getAttribute("title");
//             this.appendChild(dropdownTitle);

//             // Button
//             const dropdownButton = document.createElement("i");
//             dropdownButton.className = "material-icons dropdown-button noselect";
//             dropdownButton.textContent = "arrow_drop_down";
//             this.appendChild(dropdownButton);

//             // Search
//             if (this.searchable) {
//                 this.addSearchFunctionality();
//             }

//             let dropdownContent = this.querySelector("dropdown-content");
//             if (!dropdownContent) {
//                 dropdownContent = document.createElement("dropdown-content");
//                 this.appendChild(dropdownContent);
//             } else {
//                 this.appendChild(dropdownContent);
//             }

//             if (this.searchable) {
//                 this.insertBefore(this.searchInput, this.firstChild);
//             }
//         }

//         appendValue(name, value) {
//             const dropdownContent = this.querySelector("dropdown-content");
//             const dropdownItem = document.createElement("dropdown-item");
//             dropdownItem.className = "dropdown-item";
//             dropdownItem.textContent = name;
//             dropdownItem.setAttribute("value", name); // Add value attribute

//             if (value?.hasOwnProperty("data")) {
//                 for (const [dataName, dataValue] of Object.entries(value.data)) {
//                     dropdownItem.dataset[dataName] = dataValue;
//                 }
//             }

//             dropdownContent.appendChild(dropdownItem);
//         }

//         toggleDropdown(e) {
//             this.log("toggleDropdown invoked with event:", e, "and this:", this);
//             const dropdownContent = this.querySelector("dropdown-content"); // Changed from .dropdown-content to dropdown-content
//             if (dropdownContent) {
//                 if (dropdownContent.classList.contains("show")) {
//                     dropdownContent.classList.remove("show");
//                     this.querySelector(".dropdown-button").textContent = "arrow_drop_down";
//                 } else {
//                     dropdownContent.classList.add("show");
//                     this.querySelector(".dropdown-button").textContent = "arrow_drop_up";
//                 }
//                 e.stopPropagation();  // Prevent the event from reaching the document click handler
//             }
//         }

//         handleOutsideClick(e) {
//             // If the click is outside the dropdown, close the dropdown content
//             if (!this.contains(e.target)) {
//                 const dropdownContent = this.querySelector("dropdown-content");
//                 if (dropdownContent?.classList.contains("show")) {
//                     dropdownContent.classList.remove("show");
//                     let dropdownButton = this.querySelector(".dropdown-button");
//                     if (dropdownButton) {
//                         dropdownButton.textContent = "arrow_drop_down";
//                         this.log("Dropdown button text changed to:", dropdownButton.textContent);
//                     }
//                 }
//             }
//         }

//         setActiveItem() {
//             const settingValue = this.getAttribute("value");
//             const activeItem = this.querySelector(`[value="${settingValue}"]`);
//             if (this.activeItem) {
//                 activeItem.classList.add("active");
//                 activeItem.setAttribute("active", "");
//                 this.querySelect(".dropdown-title").textContent = activeItem.textContent;
//                 this.value = activeItem.dataset.value; // Set the value attribute
//                 this.value = activeItem.getAttribute("value");
//             }
//         }

//         handleKeyboardNavigation(e) {
//             const items = Array.from(this.querySelectorAll("dropdown-item"));
//             const activeItem = this.querySelector("dropdown-item[active]");
//             const currentIndex = items.indexOf(activeItem);

//             switch (e.key) {
//                 case "ArrowUp":
//                     const prevItem = items[currentIndex - 1] || items[items.length - 1];
//                     if (prevItem) prevItem.focus();
//                     break;
//                 case "ArrowDown":
//                     const nextItem = items[currentIndex + 1] || items[0];
//                     if (nextItem) nextItem.focus();
//                     break;
//                 case "Enter":
//                     if (document.activeElement.classList.contains("dropdown-item")) {
//                         this.selectItem(document.activeElement);
//                     }
//                     break;
//             }
//         }

//         selectItem(item) {
//             this.log("selectItem invoked with item:", item);

//             const previouslyActiveItem = this.querySelector("dropdown-item[active]");
//             this.log("Previously active item:", previouslyActiveItem);

//             // If the clicked item is already active, deselect it and revert the title
//             if (previouslyActiveItem === item) {
//                 item.removeAttribute("active");
//                 this.querySelector(".dropdown-title").textContent = this.getAttribute("title");
//                 this.value = null;
//             } else {
//                 // Deselect any previously active item
//                 if (previouslyActiveItem) {
//                     previouslyActiveItem.removeAttribute("active");
//                 }

//                 // Select the clicked item
//                 item.setAttribute("active", "");
//                 this.log("Active attribute set on item:", item);

//                 this.value = item.getAttribute("value");
//                 this.log("Value set as:", this.value);

//                 const groupName = item.closest("dropdown-contentgroup")?.getAttribute("category");
//                 if (groupName) {
//                     this.dataset.group = groupName;
//                     this.log("Group name set as:", groupName);
//                 } else {
//                     delete this.dataset.group;
//                     this.log("Group name deleted");
//                 }

//                 const titleElement = this.querySelector(".dropdown-title");
//                 this.log("Title element:", titleElement);

//                 if (titleElement) {
//                     this.log("Before:", titleElement.textContent);
//                     titleElement.textContent = item.textContent;
//                     this.log("After:", titleElement.textContent);
//                 }
//             }
//             // close the dropdown-content
//             this.closeDropdown();

//             this.dispatchEvent(new Event("change"));
//             const selectedValue = item.getAttribute("value");
//             const dropdownSelectedEvent = new CustomEvent("dropdown-item-selected", {
//                 detail: { value: selectedValue }
//             });
//             this.dispatchEvent(dropdownSelectedEvent);
//             return true;
//         }

//         addDropdownContentListener() {
//             this.querySelector("dropdown-content").addEventListener("click", (e) => {
//                 this.log("Dropdown content clicked. Target:", e.target);

//                 if (e.target.tagName.toLowerCase() === "dropdown-item") {
//                     this.log("Dropdown item clicked:", e.target);
//                     // if not multiple, this.selectItem, else
//                     if (this.multiple) {
//                         this.handleMultiSelect(e);
//                     } else {
//                         this.selectItem(e.target);
//                         this.toggleDropdown(e);
//                     }
//                 } else {
//                     this.log("Clicked inside dropdown content but not on a dropdown item");
//                 }
//             });
//         }

//         handleMouseOut(e) {
//             // this.log("Mouse out event triggered");
//             if (!this.contains(e.relatedTarget)) {
//                 this.closeDropdown();
//             }
//         }

//         handleMouseOver(e) {
//             // this.log("Mouse over event triggered");
//             if (!this.contains(e.relatedTarget)) {
//                 this.openDropdown();
//             }
//         }

//         handleClick(e) {
//             this.log("Click event triggered");
//             if (this.contains(e.target)) {
//                 if (this.querySelector(".dropdown-content.show")) {
//                     this.closeDropdown();
//                 } else {
//                     this.openDropdown();
//                 }
//             }
//         }

//         openDropdown() {
//             this.log("openDropdown invoked");
//             const dropdownContent = this.querySelector("dropdown-content");
//             if (dropdownContent) {
//                 dropdownContent.classList.add("show");
//                 this.querySelector(".dropdown-button").textContent = "arrow_drop_up";
//             }
//         }

//         closeDropdown() {
//             this.log("closeDropdown invoked");
//             const dropdownContent = this.querySelector("dropdown-content");
//             if (dropdownContent) {
//                 dropdownContent.classList.remove("show");
//                 this.querySelector(".dropdown-button").textContent = "arrow_drop_down";
//             }
//         }

//         applyDefaultStyles() {
//             const style = document.createElement('style');
//             const styleContent = `
//             * {
//                 margin: 0;
//                 padding: 0;
//                 box-sizing: border-box;
//                 --dd-radius: 20px;
//                 --dd-content-radius: 10px;
//                 --dd-content-first-radius: 10px;
//                 --dd-content-last-radius: 10px;
//             }

//             dropdown-input {
//                 position: relative;
//                 display: inline-flex;
//                 align-items: center;
//                 /* justify-content: center; */
//                 justify-content: space-between;
//                 padding: 4px 8px;
//                 gap: 4px;
//                 border-radius: var(--dd-radius);
//                 background: #fafafa;
//                 box-shadow: 0 2px 3px 0 #ccc;
//                 cursor: pointer;
//                 min-width: fit-content;
//                 max-width: 100%;
//                 min-height: auto;
//                 font-family: Arial;
//             }

//             .dropdown-pre-icon {
//                 color: #777;
//                 font-size: 16px;
//                 font-weight: bold;
//                 -webkit-user-select: none;
//                 -moz-user-select: none;
//                 -ms-user-select: none;
//                 user-select: none;
//             }

//             .dropdown-title {
//                 font-size: 14px;
//                 font-weight: bold;
//                 color: #333;
//                 margin: 0;
//                 padding: 0;
//                 text-transform: uppercase;
//                 letter-spacing: 1px;
//                 width: 100%;
//                 text-align: center;
//                 height: fit-content;
//                 -webkit-user-select: none;
//                 -moz-user-select: none;
//                 -ms-user-select: none;
//                 user-select: none;
//             }

//             .dropdown-button {
//                 /* font-size: 14px; */
//                 display: inline-flex;
//                 font-weight: bold;
//                 color: #333;
//                 margin: 0;
//                 padding: 0;
//                 /* text-transform: uppercase; */
//                 /* letter-spacing: 1px; */
//             }

//             dropdown-content {
//                 display: none;
//                 flex-direction: column;
//                 position: absolute;
//                 top: 110%;
//                 left: 0;
//                 right: 0;
//                 background: #fafafa;
//                 box-shadow: 3px 3px 16px 0 #ccc;
//                 border-radius: var(--dd-content-radius);
//                 gap: 4px;
//                 width: 100%;
//                 z-index: 1;
//             }

//             dropdown-item {
//                 font-size: 14px;
//                 font-weight: normal;
//                 color: #333;
//                 margin: 0;
//                 text-transform: uppercase;
//                 letter-spacing: 1px;
//                 cursor: pointer;
//                 white-space: pre;
//                 padding: 8px;
//                 text-decoration: none;
//                 -webkit-user-select: none;
//                 -moz-user-select: none;
//                 -ms-user-select: none;
//                 user-select: none;
//             }

//             dropdown-item:first-child {
//                 border-top-left-radius: var(--dd-content-first-radius);
//                 border-top-right-radius: var(--dd-content-first-radius);
//             }

//             dropdown-item:last-child {
//                 border-bottom-left-radius: var(--dd-content-last-radius);
//                 border-bottom-right-radius: var(--dd-content-last-radius);
//             }

//             dropdown-input:focus-within dropdown-content {
//                 display: flex;
//             }

//             dropdown-item:hover {
//                 color: #333;
//                 background: #efefef;
//                 box-shadow: 0 0 3px 0 #ccc;
//                 font-weight: 600;
//             }

//             dropdown-item[active] {
//                 /* background-color: ; */
//                 color: #007bff !important;
//             }

//             .dropdown-button {
//                 -webkit-user-select: none;
//                 -moz-user-select: none;
//                 -ms-user-select: none;
//                 user-select: none;
//             }

//             .dropdown-search {
//                 margin: 8px;
//                 border-radius: 8px;
//                 padding: 8px;
//                 outline: none;
//                 border: none;
//                 background: #efefef;
//                 color: #333;
//                 box-shadow: inset 0 0 3px 0 rgb(0 0 0 / 11%);
//             }

//             .noselect,
//             .no-select {
//                 -webkit-user-select: none;
//                 -moz-user-select: none;
//                 -ms-user-select: none;
//                 user-select: none;
//             }

//             .show {
//                 display: flex;
//             }

//             `;
//             style.textContent = styleContent;
//             this.appendChild(style);

//             const sheet = new CSSStyleSheet();
//             sheet.replaceSync(styleContent);

//             this.adoptedStyleSheets = [sheet];
//         }
//     }

//     return DropdownInput;

// })();
// customElements.define("dropdown-input", DropdownInput, { extends: "input" });

// class DropdownContent extends HTMLElement {}
// customElements.define("dropdown-content", DropdownContent);

// class DropdownItem extends HTMLElement {}
// customElements.define("dropdown-item", DropdownItem);

// class DropdownContentGroup extends HTMLElement {}
// customElements.define("dropdown-contentgroup", DropdownContentGroup);

// function convertOldDropdowns(target = document) {
// 	const oldDropdowns = target.querySelectorAll(".dropdown:not(input)");
// 	oldDropdowns.forEach((oldDropdown) => {
// 		const newDropdown = document.createElement("dropdown-input");
// 		// newDropdown.setAttribute("is", "dropdown-input");
// 		newDropdown.setAttribute("id", oldDropdown.id);
// 		newDropdown.setAttribute("title", oldDropdown.getAttribute("title"));
//         // add all the other attributes that exist on the dropdown
//         for (const { name, value } of oldDropdown.attributes) {
//             console.log(`name: ${name}\nvalue: ${value}\n`)
//             if (name !== "title") {
//                 newDropdown.setAttribute(name, value);
//             }
//         }

// 		const preIcon = oldDropdown.querySelector(".dropdown-pre-icon");
// 		if (preIcon) {
// 			newDropdown.setAttribute("data-pre-icon", preIcon.textContent);
// 		}

// 		const newDropdownContent = document.createElement("dropdown-content");
// 		const oldItems = oldDropdown.querySelectorAll(".dropdown-item");
// 		oldItems.forEach((oldItem) => {
// 			const newItem = document.createElement("dropdown-item");
// 			newItem.textContent = oldItem.textContent;
//             newItem.setAttribute("value", oldItem.textContent); // Set value attribute
//             // If there's extra data for the item, add it as dataset attributes
//             if (oldItem.dataset) {
//                 for (const [dataName, dataValue] of Object.entries(oldItem.dataset)) {
//                     newItem.dataset[dataName] = dataValue;
//                 }
//             }
//             // other attributes
//             for (const { name, value } of oldItem.attributes) {
//                 console.log(`name: ${name}\nvalue: ${value}\n`)
//                 if (name !== "value" && name !== "class") {
//                     newItem.setAttribute(name, value);
//                 }
//             }
// 			newDropdownContent.appendChild(newItem);
// 		});

// 		newDropdown.appendChild(newDropdownContent);
// 		oldDropdown.replaceWith(newDropdown);

//         // Debugging: Print the dropdown title before and after updating
//         // console.log("Before title update:", newDropdown.querySelector(".dropdown-title").textContent);
//         // console.log(newDropdown)
//         // console.log(oldDropdown)

//         // const oldActiveItems = oldDropdown.querySelectorAll(".dropdown-item.active");

//         // // Update the dropdown title if there's an active item
//         // if (oldActiveItems.length > 0) {
//         //     if (newDropdown.hasAttribute("multiple")) {
//         //         const dropdownTitle = newDropdown.querySelector(".dropdown-title");
//         //         dropdownTitle.textContent = `${oldActiveItems.length} item${oldActiveItems.length > 1 ? "s" : ""} selected`;
//         //     } else {
//         //         const dropdownTitle = newDropdown.querySelector(".dropdown-title");
//         //         dropdownTitle.textContent = oldActiveItems[0].textContent;
//         //     }
//         // } else {
//         //     const dropdownTitle = newDropdown.querySelector(".dropdown-title");
//         //     dropdownTitle.textContent = newDropdown.getAttribute("title");
//         // }

//         // // Debugging: Print the dropdown title after updating
//         // console.log("After title update:", newDropdown.querySelector(".dropdown-title").textContent);
//         // set properties for the newDropdown
//         // newDropdown.multiple = newDropdown.hasAttribute("multiple");
//         // newDropdown.searchable = newDropdown.hasAttribute("searchable") ||
//         // newDropdown.hasAttribute("search") ||
//         // newDropdown.hasAttribute("searcheable") ||
//         // newDropdown.hasAttribute("search-box");
//         // newDropdown.useExternal = newDropdown.hasAttribute("external") ||
//         // newDropdown.hasAttribute("source") ||
//         // newDropdown.hasAttribute("src");
//         // if (newDropdown.hasAttribute('regex')) {
//         //     newDropdown.useCustomRegex = true;
//         //     newDropdown.regex = newDropdown.getAttribute('regex');
//         // }
//         // // add event listeners
//         // newDropdown.addEventListener("click", newDropdown.toggleDropdown);
//         // newDropdown.addEventListener("keydown", newDropdown.handleKeyboardNavigation);
//         // newDropdown.addEventListener("dropdown-selected", newDropdown.handleDropdownSelected);
//         // document.addEventListener('click', newDropdown.handleOutsideClick);
//         // // selected item(s)
//         // newDropdown.selectedItems = new Set();
//         // newDropdown.updateSelectedItems();

// 	});
// }

// const initializeDropdown = (dropdown) => {
// 	if (!dropdown.__initialized) {
// 		dropdown.__initialized = true;
// 	}
// };

// function handleMutations(mutationsList, observer) {
// 	for (let mutation of mutationsList) {
// 		if (mutation.type === "childList") {
// 			mutation.addedNodes.forEach((node) => {
// 				if (node.nodeType === Node.ELEMENT_NODE) {
// 					// Convert old-style dropdowns if any
// 					if (node.classList.contains("dropdown-input")) {
// 						convertOldDropdowns(node);
// 					}

// 					// Also, check for any old-style dropdowns among the descendants of the added node
// 					const oldDropdowns = node.querySelectorAll(".dropdown:not(input)");
// 					oldDropdowns.forEach(convertOldDropdowns);

// 					// Initialize new-style dropdowns
// 					if (node.matches('dropdown-input')) {
// 						initializeDropdown(node);
// 					}

// 					// Also, check for any new-style dropdowns among the descendants of the added node
// 					const dropdowns = node.querySelectorAll('dropdown-input');
// 					dropdowns.forEach(initializeDropdown);
// 				}
// 			});
// 		}
// 	}
// }

// const observer = new MutationObserver(handleMutations);

// observer.observe(document.body, { childList: true, subtree: true });

// document.addEventListener("DOMContentLoaded", function () {
// 	convertOldDropdowns(); // Convert old dropdowns after document is loaded
// 	const dropdowns = document.querySelectorAll('dropdown-input');
// 	dropdowns.forEach(initializeDropdown);
// });

// function createDropdown(options) {
// 	// Create the main dropdown element
// 	const dropdown = document.createElement("dropdown-input");
// 	dropdown.setAttribute("title", options.title);

// 	if (options.preIcon) {
// 		dropdown.setAttribute("data-pre-icon", options.preIcon);
// 	}

// 	// If values are provided, populate the dropdown content
// 	if (options.values) {
// 		const dropdownContent = document.createElement("dropdown-content");

// 		for (const [name, value] of Object.entries(options.values)) {
// 			const dropdownItem = document.createElement("dropdown-item");
// 			dropdownItem.textContent = name;
//             dropdownItem.setAttribute("value", name); // Set value attribute

// 			// If there's extra data for the item, add it as dataset attributes
// 			if (value.data) {
// 				for (const [dataName, dataValue] of Object.entries(value.data)) {
// 					dropdownItem.dataset[dataName] = dataValue;
// 				}
// 			}

// 			dropdownContent.appendChild(dropdownItem);
// 		}

// 		dropdown.appendChild(dropdownContent);
// 	}

// 	return dropdown;
// }