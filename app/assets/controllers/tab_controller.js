import { Controller } from "@hotwired/stimulus";

/**
 * Tab controller
 *
 *
 */
export default class extends Controller {
    static targets = ["tabList", "tab", "tabButton"];
    static values = {
        name: String,
    };

    initialize() {
        this.tabTargets.forEach((tab, index) => {
            const tabName = tab.dataset.tabNameValue;

            const tabButton = document.createElement("a");
            tabButton.href = "#";
            tabButton.textContent = tabName;
            tabButton.setAttribute("data-tab-target", "tabButton");
            tabButton.setAttribute("data-tab-index", index);
            tabButton.setAttribute("data-action", "click->tab#changeTab");
            this.tabListTarget.appendChild(tabButton);

            if (index !== 0) {
                this.hideTabContent(tab);
            } else {
                this.setActiveButton(tabButton);
            }
        });
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
            "text-gray-500",
            "hover:text-gray-700",
            "border-transparent"
        );
        button.classList.add("text-indigo-600", "border-indigo-600");
    }

    setInactiveButton(button) {
        button.classList.remove("text-indigo-600", "border-indigo-600");
        button.classList.add(
            "text-gray-500",
            "hover:text-gray-700",
            "border-transparent"
        );
    }
}
