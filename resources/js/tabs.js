// tabs and panels parent both have a for attribute, use this to match the tab switching action, and to show, each tab and panel with have the active class

// get all the tabs
const tabsContainers = document.querySelectorAll('.tabs');
const panelsContainers = document.querySelectorAll('.panels');
let activeTPGroup = null;

// loop through each tabs container
tabsContainers.forEach((tabsContainer) => {
    const tabs = tabsContainer.querySelectorAll('.tab');
    const panels = document.querySelector(`.panels[for="${tabsContainer.getAttribute('for')}"]`).querySelectorAll('.panel');

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            activeTPGroup = tabsContainer.getAttribute('for');

            tabs.forEach((tab) => {
                tab.classList.remove('active');
            });

            tab.classList.add('active');

            panels.forEach((panel) => {
                panel.classList.remove('active');

                if (tab.getAttribute('id') === panel.getAttribute('for')) {
                    // log id and for
                    console.log(tab.getAttribute('id'), panel.getAttribute('for'));
                    panel.classList.add('active');
                }
            });
        });
    });
});
