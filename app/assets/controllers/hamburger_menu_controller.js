import { Controller } from "@hotwired/stimulus";
import { useClickOutside } from "stimulus-use";

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

    connect() {
        useClickOutside(this, {
            onlyVisible: true,
        });
    }

    initialize() {
        this.close();
    }

    open(event){
        this.menuTarget.classList.remove('translate-x-full');
    }

    close(){
        this.menuTarget.classList.add('translate-x-full');
    }

    clickOutside(){
        this.close();
    }


}


