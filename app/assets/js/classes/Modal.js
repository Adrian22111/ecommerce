class Modal {

    constructor() {
        this.modal = document.createElement('div');
        this.addCloseButton();
        this.isOpen = false;
    }

    addCloseButton(){
        const closeButton = document.createElement('button');
        const closeIcon = document.createElement('div');

        closeIcon.textContent = "X";
        closeButton.appendChild(closeIcon);
        closeButton.addEventListener('click', this.close.bind(this));

        this.modal.appendChild(closeButton);
    }
    setVariant(variant){
        switch (variant){
            case "info":
                this.modal.classList.add("info");
                break;
            case "warning":
                this.modal.classList.add("warning");
                break;
            case "error":
                this.modal.classList.add("error");
                break;
            default:
                break;
        }
    }

    open() {
        if(!this.isOpen){
            document.body.appendChild(this.modal);
            document.body.style.overflow = "hidden";
            this.isOpen = true;
        }
    }

    close(){
        if(this.isOpen){
            this.modal.remove();
            this.isOpen = false;
        }
    }

    setText(){

    }

    addButton(){

    }

    hideClosingButton(){

    }


}

export default Modal;


