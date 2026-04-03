import { Controller } from "@hotwired/stimulus";
import Dropzone from "dropzone";
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
     */
    static targets = [
        'menu'
    ];
    static values = {

    };
    initialize() {
        this.menuTarget.classList.add(
            'w-64', 'h-screen', 'fixed', 'top-0',
            'right-0', 'border-l', 'bg-gray-200',
            'overflow-y-auto', 'shadow-lg'
        );
        this.addCloseButton();
        this.close();
    }

    open(){
        this.menuTarget.classList.remove('translate-x-full');
    }

    close(){
        this.menuTarget.classList.add('translate-x-full');
    }

    addCloseButton(){
        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.innerHTML = '✕';
        closeButton.className = `
            absolute top-4 right-4
            text-gray-500 hover:text-black
            text-xl font-bold
        `;
        closeButton.setAttribute('data-action', 'click->hamburger-menu#close');

        this.menuTarget.prepend(closeButton);
    }
}


