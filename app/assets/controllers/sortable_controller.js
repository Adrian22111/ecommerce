import { Controller } from "@hotwired/stimulus";
import Sortable from 'sortablejs';
import axios from "axios";
import {useClickOutside} from "stimulus-use";


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
        const response = await axios.put(this.changeOrderUrlValue, itemsSequence);
    }
}
