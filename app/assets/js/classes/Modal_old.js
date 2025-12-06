class Modal_old {
    constructor(dialogId) {
        this.dialogId = dialogId;
        this._bindCloseHandler();
    }

    // Odświeżenie referencji do elementów modala
    _refreshElements() {
        this.dialog = document.getElementById(this.dialogId);
        if (!this.dialog) {
            console.error(`Modal with id "${this.dialogId}" not found in DOM.`);
            return false;
        }

        this.titleElem = this.dialog.querySelector("#modal-title");
        this.contentElem = this.dialog.querySelector("#modal-content");
        this.buttonsElem = this.dialog.querySelector("#modal-buttons");
        this.closeBtn = this.dialog.querySelector("#modal-close");
        return true;
    }

    // zamykanie modala
    _bindCloseHandler() {
        document.addEventListener("click", (e) => {
            if (e.target && e.target.id === "modal-close") {
                this.close();
            }
        });
    }

    open({ title = "", content = "", buttons = [] }) {
        if (!this._refreshElements()) return;

        if (this.dialog.hasAttribute("open")) {
            this.dialog.close();
        }

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
                `block w-full shadow-sm border-transparent rounded-md border p-2 mt-4 mb-2
                 hover:cursor-pointer bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700
                 dark:hover:bg-gray-600 dark:text-gray-100 text-center capitalize ${btn.class}`;
            button.addEventListener("click", () => {
                if (btn.onClick) btn.onClick();
            });
            this.buttonsElem.appendChild(button);
        });

        this.dialog.showModal();
    }

    close() {
        if (this._refreshElements() && this.dialog.hasAttribute("open")) {
            this.dialog.close();
        }
    }
}

export default Modal_old;
