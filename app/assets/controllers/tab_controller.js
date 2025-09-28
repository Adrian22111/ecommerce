import { Controller } from "@hotwired/stimulus";

/**
 * Tab controller
 *
 *
 */
export default class extends Controller {
    /**
     * tabList - div, where all tabs will be put
     * tabButton - button created dynamically inside tabList used for switching tabs
     * tab - wrapper around each tab content
     */
    static targets = ["tabList", "tab", "tabButton"];
    static values = {
        name: String, //tab name to be displayed on the button
    };

    initialize() {
        this.initializeTabButtons();
        this.setDefaultStyles();
    }

    initializeTabButtons() {
        this.tabTargets.forEach((tab, index) => {
            const tabName = tab.dataset.tabNameValue;
            const tabButton = this.createTabButton(tabName, index);
            this.tabListTarget.appendChild(tabButton);

            if (index !== 0) {
                this.hideTabContent(tab);
                this.setInactiveButton(tabButton);
            } else {
                this.setActiveButton(tabButton);
            }
        });
    }

    createTabButton(tabName, index) {
        const tabButton = document.createElement("a");
        tabButton.href = "#";
        tabButton.textContent = tabName;
        tabButton.setAttribute("data-tab-target", "tabButton");
        tabButton.setAttribute("data-tab-index", index);
        tabButton.setAttribute("data-action", "click->tab#changeTab");
        tabButton.classList.add("px-3", "py-2", "capitalize", "break-all");

        return tabButton;
    }

    setDefaultStyles() {
        this.tabListTarget.classList.add(
            "flex",
            "border-solid",
            "border-b",
            "mb-2",
            "border-gray-800",
            "flex-wrap"
        );
    }

    changeTab(event) {
        event.preventDefault();
        const selectedTabButton = event.currentTarget;
        const tabButtons = this.tabButtonTargets;

        tabButtons.forEach((button) => {
            this.setInactiveButton(button);
        });

        this.setActiveButton(selectedTabButton);

        this.tabTargets.forEach((tab) => {
            this.hideTabContent(tab);
        });

        const selectedTabIndex = event.currentTarget.dataset.tabIndex;
        this.showTabContent(this.tabTargets[selectedTabIndex]);
    }

    showTabContent(tab) {
        if (tab) {
            tab.classList.remove("hidden");
            tab.classList.add("block");
        }
    }

    hideTabContent(tab) {
        if (tab) {
            tab.classList.add("hidden");
            tab.classList.remove("block");
        }
    }

    setActiveButton(button) {
        button.classList.remove(
            "text-gray-800",
            "border-transparent",
            "hover:bg-gray-100"
        );
        button.classList.add("bg-gray-100");
    }

    setInactiveButton(button) {
        button.classList.remove("bg-gray-100");
        button.classList.add(
            "text-gray-800",
            "border-transparent",
            "hover:bg-gray-100"
        );
    }
}
