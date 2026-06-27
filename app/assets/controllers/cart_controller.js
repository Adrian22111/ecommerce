import { Controller } from "@hotwired/stimulus";
import axios, { isCancel, AxiosError } from "axios";
import Modal from "../js/classes/Modal";
import {trans} from "../translator";

/**
 * Tab controller
 *
 *
 */
export default class extends Controller {
    /**
     *
     *
     *
     */
    static targets = [];
    static values = {
        productId: Number,
    };

    initialize() {

    }

    async addToCart(e) {
        e.preventDefault();
        try {
            const response = await axios.get(
                `/cart/add/${this.productIdValue}`,
            );

            document.dispatchEvent(
                new CustomEvent('cart:updated', {
                    detail: {
                        'countItems': response.data.countItems,
                    },
                })
            );
        } catch (error) {
            const modal = new Modal();
            modal.setTitle(trans('error', {}, 'client.cart'))
                 .setText(trans('adding_failed', {}, 'client.cart'))
                 .setVariant("Error")
                 .open();
        }
    }
}
