import Modal from "./Modal";
import {trans} from "../../translator";

class ModalFactory {
    static create(type){
        const modal = new Modal();
        switch(type){
            case 'unexpectedError':
                return modal.setTitle(trans('unexpected_error_title', {}, 'modal_factory'))
                    .setText(trans('unexpected_error_body', {}, 'modal_factory'))
                    .setVariant("Error");
            default:
                throw new Error('Unhandled Modal Type');
        }
    }
}

export default ModalFactory;
