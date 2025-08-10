class Modal {
    constructor(dialogId) {
        this.dialog = document.getElementById(dialogId);
        this.titleElem = this.dialog.querySelector("#modal-title");
        this.contentElem = this.dialog.querySelector("#modal-content");
        this.buttonsElem = this.dialog.querySelector("#modal-buttons");
        this.closeBtn = this.dialog.querySelector("#modal-close");
    }

    open({ title = "", content = "", buttons = [] }) {
        this.titleElem.textContent = title;

        if (typeof content === "string") {
            this.contentElem.textContent = content;
        } else if (content instanceof HTMLElement) {
            this.contentElem.innerHTML = "";
            this.contentElem.appendChild(content);
        }

        this.buttonsElem.innerHTML = "";
        buttons.forEach((btn) => {
            const button = document.createElement("button");
            button.textContent = btn.text || "OK";
            button.className =
                "block w-full shadow-sm border-transparent rounded-md border p-2 mt-4 mb-2 hover:cursor-pointer bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100 text-center capitalize";
            button.addEventListener("click", () => {
                if (btn.onClick) btn.onClick();
            });
            this.buttonsElem.appendChild(button);
        });

        this.closeBtn.addEventListener("click", () => {
            this.dialog.close();
        });

        this.dialog.showModal();
    }

    close() {
        this.dialog.close();
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const modal = new Modal("dialog");
    window.modal = modal;
});
