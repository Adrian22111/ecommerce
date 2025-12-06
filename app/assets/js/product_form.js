import Modal from './classes/Modal.js'

document.addEventListener('DOMContentLoaded', () => {
    document.querySelector(".test-modal").addEventListener('click', (e) => {
        const modal = new Modal();
        modal.setVariant("info");
        modal.setText("Tekst tstowy modala essa");
        modal.open();
    });
})
