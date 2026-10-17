import { Controller } from "@hotwired/stimulus";
import Sortable from 'sortablejs';
import axios from "axios";
import ModalFactory from "../js/classes/ModalFactory";
import {trans} from "../translator";
import Modal from "../js/classes/Modal";


/**
 * Tab controller
 *
 *
 */
export default class extends Controller {
    /**
     *
     */
    static targets = ["imageWrapper"];
    static values = {
        itemId: String,
        changeOrderUrl: String,
        csrfToken: String,
    };

    connect() {
        this.sortable = new Sortable(this.imageWrapperTarget, {
            dataIdAttr: this.itemIdValue,
            onEnd: this.onEnd.bind(this),
        });
    }

    async onEnd(e) {
        await this.saveNewOrder(this.sortable.toArray());
    }

    async saveNewOrder(itemsSequence) {
        try {
            const response = await axios.put(this.changeOrderUrlValue, itemsSequence, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfTokenValue
                }
            });
        } catch (e) {
            if (e.response?.status === 403 && e.response?.data?.error) {
                const modal = new Modal();
                return modal.setTitle(trans('authorization_error_title', {}, 'error'))
                    .setText(e.response.data?.error)
                    .setVariant("Error")
                    .open();
            }

            return ModalFactory.create('unexpectedError').open();
        }
    }
}
