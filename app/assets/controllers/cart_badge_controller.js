import { Controller } from "@hotwired/stimulus";
import axios, { isCancel, AxiosError } from "axios";

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
    static targets = ['itemsCount'];
    connect() {
        document.addEventListener('cart:updated', this.handleCartUpdated.bind(this));
    }

    handleCartUpdated(e) {
        const countItems = e.detail.countItems;

        if (countItems > 0 && countItems < 100) {
            this.itemsCountTarget.classList.add('block');
            this.itemsCountTarget.textContent = countItems;
        } else if (countItems > 100) {
            this.itemsCountTarget.classList.add('block');
            this.itemsCountTarget.textContent = '99+';
        } else {
            this.itemsCountTarget.classList.add('hidden');
        }
    }

    disconnect() {
        document.removeEventListener('cart:updated', this.handleCartUpdated.bind(this));
    }

}
