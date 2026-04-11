import { Controller } from "@hotwired/stimulus";

/**
 * Tab controller
 *
 *
 */
export default class extends Controller {
    /**
     *
     */
    static targets = [
        'menu'
    ];
    static values = {

    };
    initialize() {
        this.close();
    }

    open(){
        this.menuTarget.classList.remove('translate-x-full');
    }

    close(){
        this.menuTarget.classList.add('translate-x-full');
    }

}


