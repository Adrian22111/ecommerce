import Modal from "./Modal";

class ModalFactory {
    static create(type){
        const modal = new Modal();
        switch(type){
            case 'unexpectedError':
                return modal.setTitle("Unexpected Error")
                    .setText("An unexpected error occurred. If the error persists, please contact the administrator.")
                    .setVariant("Error");
            default:
                throw new Error('Unhandled Modal Type');
        }
    }
}

export default ModalFactory;
