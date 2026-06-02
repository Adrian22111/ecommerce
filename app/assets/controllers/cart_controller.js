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
        } catch (error) {

        }
    }
}
