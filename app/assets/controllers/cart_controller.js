import { Controller } from "@hotwired/stimulus";

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

    addToCart(e) {
        e.preventDefault();
    }
}
