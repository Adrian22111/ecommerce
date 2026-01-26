import { twMerge } from 'tailwind-merge';
class Modal {

    isOpen = false;
    buttons = [];
    showClosingButton = true;
    closeButtonAdded = false;
    modal;
    isBodyGenerated = false;
    variant = "error";

    variantStyles = {
        dialog: {
            info:    ['bg-indigo-50', 'border-indigo-400'],
            warning: ['bg-yellow-50', 'border-yellow-400'],
            error:   ['bg-red-50', 'border-red-400'],
        },
    };

    baseStyles = {
        dialog: [
            'absolute', 'top-1/2', 'left-1/2',
            '-translate-x-1/2', '-translate-y-1/2',
            'p-5', 'rounded-md', 'border-2', 'w-xl'
        ],
        title: ['text-lg', 'font-bold'],
        body: [],
        button: [
            'block', 'w-full', 'shadow-sm',
            'border-transparent', 'text-white',
            'rounded-md', 'border', 'p-2',
            'mt-4', 'mb-2', 'hover:cursor-pointer',
            'capitalize'
        ],
        closeBtn: [
            'bg-red-600', 'hover:bg-red-700',
            'dark:bg-red-500', 'dark:hover:bg-red-400',
        ]
    };

    /**
     * Adds close button to modal
     * @returns {Modal}
     */
    addCloseButton() {
        if(this.closeButtonAdded === true){
            return this;
        }

        this.addButton(
            'Close',
            this.close.bind(this),
            this.getStyles('closeBtn')
        );
        this.closeButtonAdded = true;

        return this;
    }

    /**
     * Allows to set modal type
     * @param variant - info, warning, error
     *
     * @returns {Modal}
     */
    setVariant(variant){
        if (['info', 'warning', 'error'].includes(variant)) {
            this.variant = variant;
        }
        return this;
    }

    /**
     * Responsible for opening modal window
     */
    open() {
        if(this.isOpen)return;

        if(false === this.isBodyGenerated){
            this.generateBody();
        }

        this.modal.classList.add('block');
        document.body.appendChild(this.modal);
        this.isOpen = true;
    }

    /**
     * Responsible for closing modal window
     */
    close(){
        if(this.isOpen){
            this.modal.remove();
            this.isOpen = false;
        }
    }

    /**
     * Method allows to set text of modal window
     * @param text - modal text
     * @returns {Modal}
     */
    setText(text){
        this.modalText = text;
        return this;
    }

    /**
     * Method allows to set title of modal window
     * @param title - modal title
     * @returns {Modal}
     */
    setTitle(title) {
        this.modalTitle = title;
        return this;
    }

    /**
     * Allows to add additional buttons to modal window
     * @param text - Button text
     * @param cssClasses - additional button styling ex. "bg-red-500, text-white"
     * @param onClick - onClick function, it will be executed after button is clicked
     * @returns {Modal}
     */
    addButton(text, onClick, cssClasses){
        this.buttons.push({
            'text': text,
            'onClick': onClick,
            'cssClasses': cssClasses,
        })
        return this;
    }

    /**
     * Allows to hide closing button of modal
     * @returns {Modal}
     */
    hideClosingButton(){
        this.showClosingButton = false;
        return this;
    }

    /**
     * Method responsible for generating HTML of modal
     * @returns {Modal}
     */
    generateBody(){
        this.modal = document.createElement('dialog');
        this.modal.classList.add(
            ...this.getStyles('dialog')
        );

        const titleHTML = document.createElement("H2");
        titleHTML.textContent = this.modalTitle;
        titleHTML.classList.add(...this.getStyles('title'));

        const bodyHTML = document.createElement("P");
        bodyHTML.textContent = this.modalText;

        if(this.showClosingButton === true)this.addCloseButton();

        const buttonsHTML = document.createElement("div");

        this.buttons.forEach(button => {
           const buttonElement = document.createElement("button");
           buttonElement.textContent = button.text;
           buttonElement.onclick = button.onClick;

            const buttonFinalClasses = twMerge(
                this.getStyles('button').join(' '),
                button.cssClasses?.join(' ') ?? ''
            ).split(' ');

           buttonElement.classList.add(...buttonFinalClasses);
           buttonsHTML.appendChild(buttonElement);
        });

        this.modal.appendChild(titleHTML);
        this.modal.appendChild(bodyHTML);
        this.modal.appendChild(buttonsHTML);
        this.isBodyGenerated = true;

        return this;
    }

    /**
     *
     * @param element - string name of HTML element
     * @param variant - ex. info, warning, error
     * @returns {*|*[]}
     */
    prepareVariantStyles(element, variant) {
        return this.variantStyles[element]?.[variant] ?? [];
    }

    /**
     *
     * @param element - string name of HTML element
     * @returns {*|*[]}
     */
    prepareBasicStyles(element) {
        return this.baseStyles[element] ?? [];
    }

    /**
     *
     * @param element - string name of HTML element
     * @returns {string[]}
     */
    getStyles(element) {
        return twMerge(
            this.prepareBasicStyles(element).join(' '),
            this.prepareVariantStyles(element, this.variant).join(' ')
        ).split(' ');
    }
}

export default Modal;


